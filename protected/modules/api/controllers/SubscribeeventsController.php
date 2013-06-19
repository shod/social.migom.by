<?php

/**
 * Work with user comment
 * @package api
 */
class SubscribeEventsController extends ERestController
{
	
	public function actionPostEvent()
    {
		$entity_id = Yii::app()->request->getParam('id', 0, 'int');
		$entity_type_id = Yii::app()->request->getParam('entity_type_id', 0, 'int');
		
		$subscribeEvents = new Subscribe_Events();
		$subscribeEvents->entity_id = $entity_id;
		$subscribeEvents->entity_type_id = $entity_type_id;
		$subscribeEvents->save();
		
		Yii::app()->end();
    }
}