<?php class MessageService {
	
	static public function getUsersFromDialogId($dialog_id)
	{
		$userIds = array();
		$length = substr($dialog_id, 0, 1);
		$userIds[] = substr($dialog_id, 1, $length);
		$userIds[] = substr($dialog_id, ($length+1));
		return $userIds;
	}

	static public function getUserFromDialogId($dialog_id, $withoutId)
	{
		$userIds = array();
		$length = substr($dialog_id, 0, 1);
		$userOne = substr($dialog_id, 1, $length);
		$userTwo = substr($dialog_id, ($length+1));
		if($userOne != $withoutId){
			return $userOne;
		} else {
			return $userTwo;
		}
	}
	
	static public function generateDialogId(array $userIds)
	{
		$userTwo = array_pop($userIds);
		$userOne = array_pop($userIds);
		if($userOne < $userTwo){
			$lenght = strlen($userOne);
			$res = $lenght.$userOne.$userTwo;
		} else {
			$lenght = strlen($userTwo);
			$res = $lenght.$userTwo.$userOne;
		}
		return $res;
	}
	
	static public function sendMessageToDialog($to, $params = array(), $dialog_id = null){
		//echo 'send to chanel: ' . $channel_id;

		$render = new Render;
		$params['class'] = 'unread';
		$render->viewFile = 'application.modules.messages.views.messages.dialog._post';
		$render->setData($params);
		echo $render->html();
		$render = new Render;
		$render->viewFile = 'application.modules.messages.views.messages.dialog._post';
		$params['class'] = 'unreadMe';
		$render->setData($params);
		$html = $render->html();
		$channel_id = crc32($to . 'dialog') . $dialog_id;
		$ch = curl_init(Yii::app()->params['socialBaseUrl'].':8000/publish?cid='.$channel_id);
		// установка URL и других необходимых параметров
		//curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($ch, CURLOPT_POST, true);
		$jsonArr = array(
			'html' => $html,
			'remove' => '',
			'method' => 'append',
			'into' => '#dialog-posts',
		);
		foreach(Yii::app()->notify->categories as $cat){
			$jsonArr['menu'][$cat.'_cat'] = Yii::app()->notify->getNotifyCount($cat, $to);
		}
		$json = CJSON::encode($jsonArr);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_exec($ch);
		curl_close($ch);
	}
	
	static public function sendNotifyToDialog($to, $dialog_id, $readMessages = false){
		//echo 'send to chanel: ' . $channel_id;

		$channel_id = crc32($to . 'dialog') . $dialog_id;
		$ch = curl_init(Yii::app()->params['socialBaseUrl'].':8000/publish?cid='.$channel_id);
		// установка URL и других необходимых параметров
		//curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($ch, CURLOPT_POST, true);
		$jsonArr = array(
			'read' => ($readMessages)?'unreadMe':'unread',
		);
		foreach(Yii::app()->notify->categories as $cat){
			$jsonArr['menu'][$cat.'_cat'] = Yii::app()->notify->getNotifyCount($cat, $to);
		}
		$json = CJSON::encode($jsonArr);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_exec($ch);
		curl_close($ch);
	}
	
	static public function sendMessageToUserMessages($to, $params = array(), $fromId){
		$render = new Render;
		
		$render->viewFile = 'application.modules.messages.views.messages.posts._from';
		$render->setData($params);
		$html = $render->html();
		$jsonArr = array(
			'html' => $html,
			'remove' => '#'.$fromId,
			'method' => 'prepend',
			'withParent' => true,
			'into' => '#central_block',
		);
		foreach(Yii::app()->notify->categories as $cat){
			$jsonArr['menu'][$cat.'_cat'] = Yii::app()->notify->getNotifyCount($cat, $to);
		}
		$json = CJSON::encode($jsonArr);
		$channel_id = crc32($to . 'messages');
		$ch = curl_init(Yii::app()->params['socialBaseUrl'].':8000/publish?cid='.$channel_id);
		// установка URL и других необходимых параметров
		//curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_exec($ch);
		curl_close($ch);
	}
	
	static public function sendMessageToMyMessages($to, $params = array(), $fromId){
		$render = new Render;
		
		$render->viewFile = 'application.modules.messages.views.messages.posts._to';
		$render->setData($params);
		$html = $render->html();
		$jsonArr = array(
			'html' => $html,
			'remove' => '#'.$fromId,
			'method' => 'prepend',
			'withParent' => true,
			'into' => '#central_block',
		);
		foreach(Yii::app()->notify->categories as $cat){
			$jsonArr['menu'][$cat.'_cat'] = Yii::app()->notify->getNotifyCount($cat, $to);
		}
		$json = CJSON::encode($jsonArr);
		$channel_id = crc32($to . 'messages');
		$ch = curl_init(Yii::app()->params['socialBaseUrl'].':8000/publish?cid='.$channel_id);
		// установка URL и других необходимых параметров
		//curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true) ;
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_exec($ch);
		curl_close($ch);
	}
	
	static public function getOrderParams($first){
		$res = array();
		$before = time() - $first;
		if($before == time()){
			return $res;
		}
		$range = array(
			'hour' 	=> 60 * 60, 
			'day' 	=> 60 * 60 * 24, 
			'week' 	=> 60 * 60 * 24 * 7, 
			'month' => 60 * 60 * 24 * 30, 
			'year' 	=> 60 * 60 * 24 * 365, 
			'all' 	=> time()
		);
		
		foreach($range as $key => $val){
			$res[$key] = time() - $val;
			if($before < $val){
				break;
			}
		}
		return $res;
	}
}








 