<?php

class Api_Product extends Api_Products
{
    public $id;
    public $title;
	public $start_date;
	public $anounce_text;


    public function getCollectionName()
    {
        return 'products';
    }

    public static function model($className='Product')
	{
		return parent::model($className);
	}

}
