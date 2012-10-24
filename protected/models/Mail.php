<?php

class Mail extends CModel{
    
    const MAX_PRIORITY = 100;
    const MEDIUM_PRIORITY = 50;
    const MIN_PRIORITY = 1;
    const WORKER = 'mail send';
    
    public function attributeNames(){
        return array('template', 'params');
    }
    
    public function send(Users $user, $template, $params = array(), $fast = false){
        $criteria = new EMongoCriteria();
        $criteria->addCond('what', '==', self::WORKER);
        $criteria->addCond('user_id', '==', $user->id);
        
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
    
    public function sendAll($users, $template, $params = array(), $fast = false){
        foreach($users as $user){
            $this->send($user, $template, $params, $fast);
        }
    }
    
}