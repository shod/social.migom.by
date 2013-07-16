<?php

class Mail extends CModel{
    
    const MAX_PRIORITY = 100;
    const MEDIUM_PRIORITY = 50;
    const MIN_PRIORITY = 1;
    const WORKER = 'mail send';
	const AN_WORKER = 'mail activityNotification';
    
    public function attributeNames(){
        return array('template', 'params');
    }
    
    public function send(Users $user, $template, $params = array(), $fast = false){
        $queue = new Mongo_Queue();
        
        if($fast){
           $queue->priority = self::MAX_PRIORITY;
        } else {
            $queue->priority = self::MEDIUM_PRIORITY;
        }
        $queue->what = self::WORKER;
        $params = array_merge($params, array('template' => $template));
        $queue->user_id = $user->id;
        $queue->param = $params;
		$return = $queue->save();
		if($return && $fast){
			@exec('/h/socialmigomby/htdocs/protected/yiic mail send --actions=10');
		}
        return $return;
    }
	
	public function sendOnce(Users $user, $template, $params = array(), $fast = false){
        $criteria = new EMongoCriteria();
        $criteria->addCond('what', '==', self::WORKER);
        $criteria->addCond('user_id', '==', $user->id);

		$queue = Mongo_Queue::model()->find($criteria);
		if(!$queue){
			$queue = new Mongo_Queue();
		}
        
        if($fast){
           $queue->priority = self::MAX_PRIORITY;
        } else {
            $queue->priority = self::MEDIUM_PRIORITY;
        }
        $queue->what = self::WORKER;
        $params = array_merge($params, array('template' => $template));
        $queue->user_id = $user->id;
        $queue->param = $params;
        return $queue->save();
    }
	
	public function sendCommentsNotification($answerComment, $type, $entityTitle){
		$queue = new Queue();
		$queue->priority = self::MAX_PRIORITY;
		$queue->what = self::WORKER;
		$queue->user_id = $answerComment->parent->user_id;
        $params = array(
				'template' => 'commentNotification',
				'entityTitle' => $entityTitle,
				'answerer' => $answerComment->user->fullName,
				'answerer_id' => $answerComment->user->id,
				'answerText' => $answerComment->text,
				'time' => $answerComment->created_at,
				'link' => Mongo_News::getLink($type).$answerComment->entity_id,
				'comment_id' => $answerComment->id,
				'type' => $type,
			);
		
        $queue->param = $params;
		return $queue->save();
	}
	
	public function sendYamaAucionNotify($advert, $auction, $fromName){
		$queue = new Queue();
		$queue->priority = self::MAX_PRIORITY;
		$queue->what = self::WORKER;
		$queue->user_id = $advert['user_id'];
        $params = array(
				'image' 	=> $advert['image'],
				'template' => 'yamaAuction',
				'text' => $advert['description'],
				'user_login' => $fromName,
				'price' => $auction['price'],
				'currency' => $advert['currency'],
				'fromUser' => $auction['user_id'],
				'link' => Mongo_News::getLink('Adverts'),
				'id' => $advert['id'],
				'title' => $advert['title'],
			);
		
        $queue->param = $params;
		return $queue->save();
	}
	
	public function sendMessageNotify($modelTo, $text){
		$queue = new Queue();
		
		$queue->priority = self::MAX_PRIORITY;
		$queue->what = self::WORKER;
		$queue->user_id = $modelTo->user_id;
        $params = array(
				'template' => 'messageNotification',
				'text' => $text,
				'sender' => $modelTo->sender->fullName,
				'sender_id' => $modelTo->sender_id,
				'time' => $modelTo->textTable->created_at,
				'link' => Yii::app()->params['socialBaseUrl'].'/messages/send/'.$modelTo->sender_id,
			);
		
        $queue->param = $params;
		return $queue->save();
	}
	
	public static function addActivityNotification($user_id){
		$criteria = new EMongoCriteria();
        $criteria->addCond('what', '==', self::AN_WORKER);
        $criteria->addCond('user_id', '==', $user_id);

		$queue = Queue::model()->find($criteria);
		if(!$queue){
			$queue = new Queue();
		}
		
		$queue->priority = self::MIN_PRIORITY;
		$queue->what = self::AN_WORKER;
		$queue->user_id = $user_id;
        $queue->param = array(
			'template' => 'activityNotification',
		);
		return $queue->save();
	}
    
	public static function deleteActivityNotification($user_id){
		$criteria = new EMongoCriteria();
        $criteria->addCond('what', '==', self::AN_WORKER);
        $criteria->addCond('user_id', '==', $user_id);
	
		$queue = Queue::model()->find($criteria);
		if($queue){
			$queue->delete();
		}
		return true;
	}
	
    public function sendAll($users, $template, $params = array(), $fast = false){
        foreach($users as $user){
            $this->send($user, $template, $params, $fast);
        }
    }
	
	public function sendCommentsAuthorNotification($answerComment, $type, $entityTitle, $authorId){
		$user = Users::model()->findByPk($authorId);
		$queue = new Queue();
		$queue->priority = self::MAX_PRIORITY;
		$queue->what = self::WORKER;
		$queue->user_id = $authorId;
        $params = array(
				'template' => 'commentAuthorNotification',
				'entityTitle' => $entityTitle,
				'answerer' => ($answerComment->user->profile->name)? $answerComment->user->profile->name : $answerComment->user->login,
				'answerer_id' => $answerComment->user->id,
				'answerText' => $answerComment->text,
				'time' => $answerComment->created_at,
				'link' => Mongo_News::getLink($type).$answerComment->entity_id,
				'comment_id' => $answerComment->id,
				'type' => $type,
			);
		
        $queue->param = $params;
		return $queue->save();
	}
	
	public function sendDigest($usersDigest){
		
		foreach($usersDigest as $user_id => $user){
			$params = array(
				'template' => 'weeklyDigets',
			);
			foreach($user as $entityName => $entities){
				switch($entityName){
					case'adverts':
						$adverts = Api_Adverts::model()->getAdverts(array('ids' => $entities));
						if($adverts){
							foreach($adverts as $adv){
								$params['adverts'][] = array(
									'type' => 'adverts',
									'id' => $adv->id,
									'title' => $adv->description,
									'created_at' => $adv->created_at,
									'image' => $adv->image,
									'authorName' => $adv->name,
									'authorId' => $adv->user_id,
									'price' => $adv->price,
									'currency' => $adv->currency,
								);
							}
						}
						break;
					case'news':
						$adverts = Api_News::model()->getByIds(array('ids' => $entities));
						if($adverts->success){
							unset($adverts->success);
							foreach($adverts as $adv){
								$params['news'][] = array(
									'type' => 'news',
									'id' => $adv->id,
									'title' => $adv->title,
									'created_at' => $adv->start_date,
									'image' => 'http://static.migom.by/img/news/' .$adv->id. '/main-medium.jpg',
								);
							}
						}
						break;
					case'article':
						$adverts = Api_Article::model()->getByIds(array('ids' => $entities));
						if($adverts->success){
							unset($adverts->success);
							foreach($adverts as $adv){
								$params['article'][] = array(
									'type' => 'article',
									'id' => $adv->id,
									'title' => $adv->title,
									'created_at' => $adv->start_date,
									'image' => 'http://static.migom.by/img/articles/img$' .$adv->id. '.jpg',
								);
							}
						}
						break;
				}
			}
			
			$user = Users::model()->findByPk($user_id);
			$queue = new Queue();
			$queue->priority = self::MEDIUM_PRIORITY;
			$queue->what = self::WORKER;
			$queue->user_id = $user_id;
			
			$queue->param = $params;
			if($user_id == 1 || $user_id == 5346 || $user_id == 26){
				$queue->save();
			}
			
		}
	}
}