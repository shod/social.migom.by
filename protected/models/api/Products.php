<?php

class Api_Products extends ERestDocument
{
    public function attributeNames()
    {
        return array();
    }

    public function getCollectionName()
    {
        return 'products';
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