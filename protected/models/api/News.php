<?php

class Api_News extends ERestDocument
{
    public $id;
    public $title;


    public function getCollectionName()
    {
        return 'news';
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

    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
