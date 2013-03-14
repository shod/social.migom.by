<?php

class Api_News_Author extends ERestDocument
{
    public $id;
    public $title;
	public $start_date;
	public $anounce_text;
	public $user_id;


    public function getCollectionName()
    {
        return 'news_author';
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

    public static function model($className='News_Author')
	{
		return parent::model($className);
	}

}
