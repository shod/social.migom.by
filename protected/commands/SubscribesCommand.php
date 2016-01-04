<?php
// yiic mail send --actions=10
class SubscribesCommand extends ConsoleCommand {

	public $fromQueue = false;
	public $limitThemeNews = 3;
	
	public function actionCreateDayNewsEvents(){
		$news = Api_News::model()->getDayEvents();
		if(!$news->success){
			Yii::log(array('message' => 'not connect to migom'), CLogger::LEVEL_ERROR, 'console');
			return false;
		}
		unset($news->success);
		foreach($news as $new){
			$subscribeEvents = new Subscribe_Events();
			$subscribeEvents->entity_id = $new->id;
			$subscribeEvents->entity_type_id = 1; //news
			$tags = Tags::model();
			$textToTags = $new->anounce_text . ' ' . $new->title;
			$tags->postProductLink(array('text' => $textToTags, 'entity_type_id' => 1, 'entity_id' => $new->id));
			$subscribeEvents->save();
		}
	}
	
	public function actionCreateDayNewsEventsArticle(){
		$news = Api_Article::model()->getDayEvents();
		if(!$news->success){
			Yii::log(array('message' => 'not connect to migom'), CLogger::LEVEL_ERROR, 'console');
			return false;
		}
		unset($news->success);
		foreach($news as $new){
			$subscribeEvents = new Subscribe_Events();
			$subscribeEvents->entity_id = $new->id;
			$subscribeEvents->entity_type_id = 3; //news
			$tags = Tags::model();
			$textToTags = $new->anounce_text . ' ' . $new->title;
			$tags->postProductLink(array('text' => $textToTags, 'entity_type_id' => 3, 'entity_id' => $new->id));
			$subscribeEvents->save();
		}
	}

	/*
	* Отправка рассылки пользователям
	*/
    public function actionSetMails() {
		$events = Subscribe_Events::model()->findAll(array('condition' => 'is_weekly_send = 0 and DATEDIFF(now(), FROM_UNIXTIME(created_at))  < 200'));		
		$subscribes = Subscribes::model()->findAll();
		//$subscribes[] = array('user_id'=>5346); //shod
		$subscribers = array();		
		
		foreach($subscribes as $sub){	
			
			//if(isset($sub) && $sub->user_id == 12482){
				$criteria = new EMongoCriteria();			
				$criteria->addCond('user_id', '==', $sub->user_id);
				$news = Mongo_News::model()->find($criteria);

				if(!isset($news->disable_notify['weekly_digest'])){
					if(!isset($subscribers[$sub->user_id]['tags'])){
						$subscribers[$sub->user_id]['tags'] = array();
					}
					if(!isset($subscribers[$sub->user_id]['tagGroups'][$sub->time_group])){
						$subscribers[$sub->user_id]['tagGroups'][$sub->time_group] = array();
					}
					array_push($subscribers[$sub->user_id]['tags'], $sub->tag_id);
					array_push($subscribers[$sub->user_id]['tagGroups'][$sub->time_group], $sub->tag_id);
				}
			//}
			
		}		
		
		
		$bestEntitiesForUser = array();
		
		$newsIds = array();
		$ahimsaIds = array();
		$productsIds = array();
		$articlesIds = array();
		foreach($events as $event){
			switch($event->entity_type_id){
				case 1:
					$newsIds[] = $event->entity_id;
					break;
				case 2:
					$productsIds[] = $event->entity_id;
					break;
				case 3:
					$articlesIds[] = $event->entity_id;
					break;
				case 4:
					$ahimsaIds[] = $event->entity_id;
					break;
				
			}
		}
		
		
		
		$bestEntitiesForUser = $this->getPart($ahimsaIds, $subscribers, $bestEntitiesForUser, 'adverts');
		$bestEntitiesForUser = $this->getPart($newsIds, $subscribers, $bestEntitiesForUser, 'news');
		$bestEntitiesForUser = $this->getPart($articlesIds, $subscribers, $bestEntitiesForUser, 'article');
		
		//var_dump($bestEntitiesForUser);
		/*Создаем модель Mail*/
		$mail = new Mail;
		$mail->sendDigest($bestEntitiesForUser);
		//Subscribe_Events::model()->updateAll(array('is_weekly_send' => 1), 'is_weekly_send = 0');
		
    }
	
	protected function getPart($ids, $subscribers, $bestEntitiesForUser, $theme)
	{
		switch($theme){
			case'adverts':
				$typeId = 4;
				break;
			case'news':
				$typeId = 1;
				break;
			case'article':
				$typeId = 3;
				break;
			case'product':
				$typeId = 2;
				break;
			default:
				return $bestEntitiesForUser;
		}
		if(!$ids && count($ids) == 0){
			return $bestEntitiesForUser;
		}
		//var_dump($ids);
		//var_dump($typeId);
		$modelTags = Tags::model();
		//$modelTags->debug=1;
		
		$tagsModele = $modelTags->getTagsForEntities(array('entities' => $ids, 'entity_type_id' => $typeId));
		
		$tags = array();
		
		if($tagsModele){
			$tags = $tagsModele->message;
		}
		
		$entities = array();
		foreach($tags as $tag){
			$entities[$tag->entity_id][] = $tag->tag_id;
		}
		
		foreach($subscribers as $user => $sub){
			foreach($entities as $entity => $entityTags){
				foreach($sub['tags'] as $tag){
					if(in_array($tag, $entityTags)){
						if(!isset($bestEntitiesForUser[$user][$theme][$entity])){
							$bestEntitiesForUser[$user][$theme][$entity] = 1;
						} else {
							$bestEntitiesForUser[$user][$theme][$entity]++;
						}
					}
				}
				$groupElements = array();
				foreach($sub['tagGroups'] as $group => $tagGroup){
					$groupElements[$group] = 0;
					foreach($tagGroup as $tag){
						if(in_array($tag, $entityTags)){
							$groupElements[$group]++; 
						}
					}
				}
				foreach($sub['tagGroups'] as $group => $tags){
					if(count($sub['tagGroups'][$group]) == $groupElements[$group]){
						$bestEntitiesForUser[$user][$theme][$entity] = $bestEntitiesForUser[$user][$theme][$entity] + 10;
					}
				}
			}
			$this->_getTopUserThemeEntities(&$bestEntitiesForUser, $user, $theme);
			
		}
		return $bestEntitiesForUser;
	}
	
	private function _getTopUserThemeEntities(&$bestEntitiesForUser, $user, $theme){
		if(isset($bestEntitiesForUser[$user][$theme])){
			arsort($bestEntitiesForUser[$user][$theme]);
			$bestEntitiesForUser[$user][$theme] = array_keys($bestEntitiesForUser[$user][$theme]);
			$bestEntitiesForUser[$user][$theme] = array_slice($bestEntitiesForUser[$user][$theme],0,$this->limitThemeNews);
		}
		
	}
	
}