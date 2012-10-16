<?php

class UserController extends Controller
{
        public $layout = 'user';
        public $title = 'User Controller Param(change in action)';
        
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
                            'actions'=>array('edit', 'deletenew'),
                            'roles' => array('user', 'moderator', 'administrator')
                        ),
                        array('allow', // allow readers only access to the view file
                            'actions'=>array('index', 'createUserAvatar'),
                            'users' => array('*')
                        ),
                        array('deny',   // deny everybody else
                            'users' => array('*')
                        ),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
            $id = Yii::app()->request->getParam('id', Yii::app()->user->id);
            if(!$id){
                $this->redirect('/site/login');
            }
            
//            $criterea = new EMongoCriteria();
//            $criterea->addCond('user_id', '==', Yii::app()->user->id);
//            
//            $news = News::model()->find($criterea);
//            if(!$news){
//                $news = new News();
//                $news->user_id = Yii::app()->user->id;
//            }
//            for($i = 1; $i < 3; $i++){
//                $entity = new NewsEntity();
//                $entity->name = 'notification';
//                $entity->template = 'notification';
//                $entity->id = $i;
//                $entity->params['param1'] = $i;
//                $entity->params['param2'] = 'Notification: '.$i;
//                $news->entities[] = $entity;
//            }
//            
//            $news->save();
            
            $model = $this->loadModel($id);
            $criterea = new EMongoCriteria();
            $criterea->addCond('user_id', '==', Yii::app()->user->id);
            $news = News::model()->find($criterea);
            $this->render('profile', array('model' => $model, 'news' => $news));
	}
        
        public function actionDeleteNew($entity, $id){
            $criteria = new EMongoCriteria;
            $criteria->addCond('user_id', 'equals', Yii::app()->user->id);
            $news = News::model()->find($criteria);
            foreach($news->entities as $key => $en){
                if($en->name == $entity && $en->id == $id){
                    unset($news->entities[$key]);
                }
            }
            return $news->save();
        }
        
        public function actionEdit()
	{
            if(Yii::app()->user->getIsGuest()){
                throw new CHttpException(404,'The requested page does not exist.');
            }
            $id = Yii::app()->user->id;
            $model = $this->loadModel($id);
            $model->setScenario('general_update');
            
            if (Yii::app()->getRequest()->isAjaxRequest && Yii::app()->getRequest()->getParam('ajax') == 'generalForm') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            
            if (Yii::app()->getRequest()->isAjaxRequest && Yii::app()->getRequest()->getParam('ajax') == 'profileForm') {
                $model->profile->setScenario('update');
                echo CActiveForm::validate($model->profile);
                Yii::app()->end();
            }
            
            $redirect = false;
            $success = true;
            
            if(isset($_POST['Users_Profile'])){
                if(isset($_FILES['Users_Profile']['tmp_name']) && $_FILES['Users_Profile']['tmp_name']['avatar']){
                    $upRes = UserService::uploadAvatar($id, $_FILES['Users_Profile']['tmp_name']);
                    $model->profile->avatar = $upRes['success'];
                    if(!$model->profile->avatar){
                        $model->profile->addError('avatar', $upRes['error']);
                    }
                }
                $model->profile->setScenario('update');
                $model->profile->attributes = $_POST['Users_Profile'];
                if($model->profile->validate() && $model->profile->save()){
                    $redirect = true;
                } else {
                    $redirect = false;
                    $success = false;
                }
            }

            if(isset($_POST['Users'])){
                $model->setScenario('general_update');
                $model->attributes = $_POST['Users'];
                if($model->validate() && $model->save() && $success){
                    $redirect = true;
                } else {
                    $redirect = false;
                }
            }
            
            if($redirect){
                $this->redirect('/user/index');
            }
            
            $this->render('edit', array('model' => $model));
	}
        
        public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        public function actionCreateUserAvatar($id){
            $user = Users::model()->findByPk($id);
            $file = Yii::app()->basePath.'/../images/users/'.$id.'/avatar.jpg';
            if(!file_exists($file) && $user){
                $srcImage = UserService::uploadAvatarFromEmail($user->id, $user->email);
                $file = Yii::app()->basePath.'/..'.$srcImage;
                $image = Yii::app()->image->load($file);
                $image->render();
            } else {
                return false;
            }
        }
}
