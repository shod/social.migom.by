<?php

class Api_Mail_Digest extends ERestDocument
{
    public $news_id;
    public $ancor;
	public $url;
	public $descr;
	public $type;

    public function getCollectionName()
    {
        return 'mail_digest';
    }
	
	public function getRestComponent()
	{
		return $this->setRestComponent(Yii::app()->getComponent('migom'));
	}

    public static function model($className='mail_digest')
	{
		return parent::model($className);
	}

}
