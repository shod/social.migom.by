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
        $queue = new Queue();
        
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
	
	public function sendOnce(Users $user, $template, $params = array(), $fast = false){
        $criteria = new EMongoCriteria();
        $criteria->addCond('what', '==', self::WORKER);
        $criteria->addCond('user_id', '==', $user_id);

		$queue = Queue::model()->find($criteria);
		if(!$queue){
			$queue = new Queue();
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
				'link' => News::getLink($type).$answerComment->entity_id,
				'comment_id' => $answerComment->id,
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
				'link' => News::getLink($type).$answerComment->entity_id,
				'comment_id' => $answerComment->id,
			);
		
        $queue->param = $params;
		return $queue->save();
	}
    
}