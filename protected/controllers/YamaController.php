<?php

class YamaController extends Controller
{

    public $layout = 'user';
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
                'actions' => array('index', 'delete'),
                'roles' => array('user', 'moderator', 'administrator')
            ),
            array('deny', // deny everybody else
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
		$criterea = new EMongoCriteria();
        $criterea->addCond('user_id', '==', Yii::app()->user->id);
		$news     = News::model()->find($criterea);
		
		if(Yii::app()->request->getParam('filter')){
			if (isset($news->disable_entities[Yii::app()->request->getParam('filter')])) {
				unset($news->disable_entities[Yii::app()->request->getParam('filter')]);
			} elseif(Yii::app()->request->getParam('filter')) {
				$news->disable_entities[Yii::app()->request->getParam('filter')] = Yii::app()->request->getParam('filter');
			}
			$news->save();
		}
	
		$offset = Yii::app()->request->getParam('offset', 0, 'int');
		$adverts = Api_Adverts::model();
		$adverts = $adverts->getByUser(Yii::app()->user->id, 
							array(
								'limit' => UserNews::NEWS_ON_WALL+1, 
								'offset' => $offset,
								'with' => 'auction',
							));
		
		$uIds = array();
		foreach($adverts as $adv){
			foreach($adv->auctions as $auc){
				$uIds[] = $auc->user_id;
			}
		}
		$uIds = array_unique($uIds);
		$users = array();
		if(count($uIds)){
			$users = Users::model()->findAll('id IN (' . implode(',', $uIds) . ')');
		}
		
		$resU = array();
		foreach($users as $u){
			$resU[$u->id] = $u;
		}
		$model    = Users::model()->findByPk(Yii::app()->user->id);
		
		$more = false;
		if(count($adverts) > UserNews::NEWS_ON_WALL){
			array_pop($adverts);
			$more = true;
		}
		
		if(Yii::app()->request->isAjaxRequest){
			$this->renderPartial('_advert', 
				array( 
					'adverts' => $adverts,
					'model' => $model,
					'more' => $more,
					'offset' => $offset,
					'users' => $resU,
				)
			);
			Yii::app()->end();
		}
		
		$this->render('index',
			array(
				'news' => $news, 
				'model' => $model, 
				'adverts' => $adverts, 
				'more' => $more, 
				'offset' => $offset,
				'users' => $resU,
			));
        
    }
	
	public function actionDelete($id){
		if(Yii::app()->request->isAjaxRequest){
			Things::model()->deleteByPk($id);
			return true;
		}
	}

}
