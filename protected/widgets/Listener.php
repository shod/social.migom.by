<?php
class Listener extends QWidget {

	public $viewFile = 'listener';
	protected $_channelId;
	
	public function init(){
		if(Yii::app()->user->isGuest){
			return false;
		}
		$this->_channelId = $this->generateChannelId();
	}
	
	public function generateChannelId($prefix = null, $code = null){
		return $this->_channelId = crc32(Yii::app()->user->id . $prefix) . $code;
	}
	
	public function getChannelId(){
		return $this->_channelId;
	}
}