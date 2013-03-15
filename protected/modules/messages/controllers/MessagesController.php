<?php

class MessagesController extends Controller
{

	public $layout = 'application.views.layouts.user';
    public $title  = '';

	public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow readers only access to the view file
                'actions' => array(),
                'roles' => array('user', 'moderator', 'administrator')
            ),
            array('deny', // deny everybody else
                'users' => array('*')
            ),
        );
    }
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionSend($id)
	{
		$model=new Messages;
		
		$toUser = Users::model()->with('profile')->findByPk($id);
		$user = Users::model()->findByPk(Yii::app()->user->id);
		$dialog_id = MessageService::generateDialogId(array($id ,Yii::app()->user->id));
		
		if(!$toUser){
			throw new CHttpException(404, Yii::t('Site', 'Пользователь не найден'));
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Messages_Text']) && Yii::app()->request->isAjaxRequest)
		{
			$model->user_id = Yii::app()->user->id;
			$model->sender_id = Yii::app()->user->id;
			$model->dialog_id = $dialog_id;
			if($model->user_id == $model->sender_id){
				$model->to = $id;
			} else {
				$model->to = 0;
			}
			
			$textModel = new Messages_Text();
			$textModel->attributes = Yii::app()->request->getParam('Messages_Text');
			$textModel->status = Messages_Text::UNREAD;
			if(!$textModel->text){
				return false;
			}
			
			if($model->validate() && $textModel->validate()){
				
				$transaction = Yii::app()->db->beginTransaction();
				try{
					$textModel->save();
					$model->message_id = $textModel->id;
					$modelTo = clone($model);
					$modelTo->user_id = $id;
					$model->save();
					$modelTo->save();
					$transaction->commit();
				}
				catch (Exception $e)
				{
					$transaction->rollBack();
				}
				
				$params = array(
					'dialog' => array(
						'to' => $modelTo->user_id,
						'text_status' => $textModel->status,
						'sender_id' => $model->user_id,
						'created_at' => $textModel->created_at,
						'text' => $textModel->text,
					),
					'users' => array(
						$modelTo->user_id => $modelTo->user,
						$model->user_id => $model->user,
					),
					'message' => $model,
				);

				Yii::app()->notify->setUserNotifyCount($id, 'messages', Messages::model()->getCountUnreadMessages($id));
				MessageService::sendMessageToDialog($id, $params, $dialog_id);
				// TODO - прекратить генерацию страницы пользователю
				MessageService::sendMessageToUserMessages($id, $params, Yii::app()->user->id);
				MessageService::sendMessageToMyMessages(Yii::app()->user->id, $params, $id);
				
				$criteria = new EMongoCriteria();
				$criteria->addCond('user_id', '==', $user_id);

				$news = News::model()->find($criteria);
				
				if(!Yii::app()->cache->get('online_user_' . $modelTo->user_id) && !isset($news->disable_notify['messages_activity'])){
					$mail = new Mail;
					$mail->sendMessageNotify($modelTo, $textModel->text);
				}
			}
			Yii::app()->end(); // END PAGE!!
		}
		
		if(Yii::app()->request->getParam('date', '', 'int')){
			$criteria = array(
				'condition' => 'dialog_id = :dId AND user_id = :userId AND textTable.created_at > :created_at',
				'params' => array(
					':created_at' => Yii::app()->request->getParam('date', 'int'),
					':userId' => Yii::app()->user->id,
					':dId' => MessageService::generateDialogId(array($id ,Yii::app()->user->id)),
				),
				'order' => 'message_id DESC',
			);
		} else {
			$criteria = array(
				'condition' => 'dialog_id = :dId AND user_id = :userId',
				'params' => array(
					':userId' => Yii::app()->user->id,
					':dId' => MessageService::generateDialogId(array($id ,Yii::app()->user->id)),
				),
				'limit' => Messages::MESSAGES_ON_DIALOG,
				'order' => 'message_id DESC',
			);
		}
		
		$messages = Messages::model()
						->with('textTable')
						->with('sender')
						->findAll($criteria);
						
		$messages = array_reverse($messages);
		
		$first = Messages::model()->with('textTable')->find(array(
							'select' => 'textTable.created_at as created_at', 'condition' => 'dialog_id = :dId AND user_id = :userId', 
							'order' => 'message_id ASC',
							'limit' => 1,
							'params' => array(':userId' => Yii::app()->user->id,':dId' => MessageService::generateDialogId(array($id ,Yii::app()->user->id)))));

		Widget::get('listener')->generateChannelId('dialog', $dialog_id);
		
		$this->render('dialog',array(
			'model'		=>$user,
			'user'		=>$toUser,
			'textModel'	=>new Messages_Text(),
			'messages' 	=> $messages,
			'first'		=> ($first) ? $first->textTable->created_at : 0,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Messages('search');
		if(Yii::app()->request->isAjaxRequest){
			$this->renderPartial('posts/_posts',array(
				'model'=>$model,
			));
			Yii::app()->end();
		}
			
		Widget::get('listener')->generateChannelId('messages');

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Messages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionReadDialog($id){
		$dialog_id = MessageService::generateDialogId(array($id, Yii::app()->user->id));
		$unread = Messages::model()->with('textTable')->findAll(array('select' => 't.message_id', 'condition' => 'dialog_id = :dialog_id AND textTable.status = :unread AND user_id = :uId', 'params' => array(':dialog_id' => $dialog_id, ':unread' => Messages_Text::UNREAD, 'uId' => Yii::app()->user->id)));
		$listMessIds = CHtml::listData($unread, 'message_id','message_id');
		if(!empty($listMessIds)){
			Messages_Text::model()->with('messages')->updateAll(array('status' => Messages_Text::READ), array('condition' => 'id IN ('. implode(',', $listMessIds) .')'));
		}
		Yii::app()->notify->removeOneNotify(Yii::app()->user->id, 'messages');
		MessageService::sendNotifyToDialog($id, $dialog_id);
		MessageService::sendNotifyToDialog(Yii::app()->user->id, $dialog_id, true);
	}
}
