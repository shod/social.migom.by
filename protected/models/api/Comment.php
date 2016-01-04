<?php

class Api_Comment extends ERestDocument
{
    public $id;
    public $title;
	public $start_date;
	public $anounce_text;


    public function getCollectionName()
    {
        return 'comment';
    }

    public static function model($className='Comment')
	{
		return parent::model($className);
	}
	
	public function getRestComponent()
	{
		return $this->setRestComponent(Yii::app()->getComponent('migom'));
	}

}
