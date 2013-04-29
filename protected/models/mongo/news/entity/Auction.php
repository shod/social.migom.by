<?php
class News_Entity_Auction extends EMongoEmbeddedDocument
{
    
    public $user_id;
    public $id;
    public $price;
    public $login;
	public $currency;
    
    // We can define rules for fields, just like in normal CModel/CActiveRecord classes
    public function rules()
    {
        return array(
            array('user_id, price, login, currency', 'required'),
        );
    }
}