<?php

/**
 * Work with user comment
 * @package api
 */
class SubscribeController extends ERestController
{
	
	public function actionPostNew()
    {
		$user_id = Yii::app()->request->getParam('id', 0, 'int');
		$is_search = Yii::app()->request->getParam('is_search', 0, 'list', array(0,1));
		
		$text = Yii::app()->request->getParam('text');
		$tags = Tags::model()->getTags(array('text' => $text));
		$time = time();
		
		if(!$tags){
			$content = array(ERestComponent::CONTENT_SUCCESS => false, ERestComponent::CONTENT_MESSAGE => 'tag not found');
			$this->render()->sendResponse($content);
			Yii::app()->end();
		}
		
		$count = count($tags);
		$stags = implode(',', $tags);
		
		$res = Subscribes::model()->findAll(array('select' => 'count(1) as group_count, user_id, tag_id','condition' => 'user_id = :u AND tag_id IN('.$stags.') AND is_search = :is_search', 'params' => array(':u' => $user_id, ':is_search' => $is_search), 'group' => 'time_group'));
		foreach($res as $r){
			if($r->group_count == $count){
				$content = array(ERestComponent::CONTENT_SUCCESS => false, ERestComponent::CONTENT_MESSAGE => 'the text in subscribe');
				$this->render()->sendResponse($content);
				Yii::app()->end();
			}
		}

		foreach($tags as $tag){
			$sub = new Subscribes();
			$sub->user_id = $user_id;
			$sub->tag_id = $tag;
			$sub->time_group = $time;
			$sub->is_search = $is_search;
			$sub->save();
		}
		
		Yii::app()->end();
    }
}