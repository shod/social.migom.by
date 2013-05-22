<?php

class Api_Adverts extends ERestDocument
{
	public static $currencySymbol = array(0 => '$', 1 => 'BYR', 2 => 'RUR');

    public $id;
    public $description;
	public $title;
	public $created_at;
	public $text;
	public $user_id;		
	public $start_date;		// from news
	public $anounce_text;	// from news
	public $currency;

    public function getCollectionName()
    {
        return 'Adverts';
    }
	
	protected function afterFind()
	{
		$this->start_date = $this->created_at;
		$this->anounce_text = $this->description;
		$this->title = mb_substr($this->description, 0, 50, 'utf8');
		return parent::afterFind();
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

    public static function model($className='Adverts')
	{
		return parent::model($className);
	}

}
