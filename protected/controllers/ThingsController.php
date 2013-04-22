<?php

class ThingsController extends Controller
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
                'actions' => array('index', 'delete', 'setHave'),
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
	
        $model    = Users::model()->findByPk(Yii::app()->user->id);
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
		
		$criteria=new CDbCriteria();
		$criteria->compare('user_id', Yii::app()->user->id);
		if(isset($news->disable_entities['have'])){
			$criteria->addCondition('have != 1');
		}
		if(isset($news->disable_entities['wish'])){
			$criteria->addCondition('have != 0');
		}
		$criteria->order = 'created_at DESC';
		$things = Things::model()->findAll($criteria);
		
		$pIds = array();
		foreach($things as $t){
			$pIds[] = $t->product_id;
		}
		
		$countHaventProducts = array();
		$thingsProds = array();
		if(count($pIds)){
			$criterea = new EMongoCriteria();
			$criterea->addCond('product_id', 'in', $pIds);
			$thingsProds = Info_Products::model()->findAll($criterea);
			$countHaventProducts = Things::getCountThingsGroupByProduct($pIds);
		}
		$apiIds = array();
		$res = array();
		foreach($things as $thing){
			$r = array();
			$r['id'] = $thing->id;
			$r['product_id'] = $thing->product_id;
			$r['have'] = $thing->have;
			$r['haveCont'] = $countHaventProducts[$thing->product_id];
			foreach($thingsProds as $key => $pr){
				if($thing->product_id == $pr->product_id){
					$r['section_id'] = $pr->section_id;
					$r['name'] = $pr->name;
					unset($thingsProds[$key]);
					break;
				}
			}
			if(!isset($r['name'])){
				$apiIds[] = $thing->product_id;
			}
			$res[$thing->product_id] = $r;
		}
		if(count($apiIds)){
			
			$api = Api_Product::model();
			$apiRes = $api->getInfo('attr', array('id' => $apiIds, 'list' => array('title', 'id', 'section')));
			foreach($apiRes as $arp){
				$thProd = Info_Products::model();
				$thProd->product_id = $arp->id;
				$thProd->section_id = $arp->section;
				$thProd->name = $arp->title;
				$thProd->save();
				$res[$thProd->product_id]['section_id'] = $thProd->section_id;
				$res[$thProd->product_id]['name'] = $thProd->name;
			}
			
		}
		
		if(Yii::app()->request->isAjaxRequest){
			$this->renderPartial('things', 
					array(
						'res' => $res,
					)
				);
			Yii::app()->end();
		}
		
        $this->render('index', 
						array(
							'model' => $model, 
							'news' => $news, 
							'things' => $things, 
							'res' => $res,
						)
					);
    }
	
	public function actionDelete($id){
		if(Yii::app()->request->isAjaxRequest){
			Things::model()->deleteByPk($id);
			return true;
		}
	}
	
	public function actionSetHave($id){
		if(Yii::app()->request->isAjaxRequest){
			$thing = Things::model()->findByPk($id);
			$thing->have = 1;
			$thing->save();
			return true;
		}
		
	}
}
