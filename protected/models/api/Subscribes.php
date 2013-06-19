<?php

class Api_Subscribes extends ERestDocument
{
    public $user_id;
    public $tag_id;
	public $group;
	
    public function getCollectionName()
    {
        return 'Subscribes';
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
		return $this->setRestComponent(Yii::app()->getComponent('yama'));
	}

    public static function model($className='Subscribes')
	{
		return parent::model($className);
	}

}
