<?php

class Api_Article extends ERestDocument
{
    public $id;
    public $title;
	public $start_date;
	public $anounce_text;


    public function getCollectionName()
    {
        return 'article';
    }

    /**
	 * Get ERest component instance
	 * By default it is ERest application component
	 *
	 * @return ERest
	 * @since v1.0
	 */
	public function getRestComponent()
	{
		return $this->setRestComponent(Yii::app()->getComponent('migom'));
	}

    public static function model($className='Article')
	{
		return parent::model($className);
	}

}
