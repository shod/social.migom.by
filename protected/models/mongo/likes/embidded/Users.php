<?php

class Likes_Embidded_Users extends EMongoEmbeddedDocument
{

    public $id;
    public $weight;
    public $login;

    // We can define rules for fields, just like in normal CModel/CActiveRecord classes
    public function rules()
    {
        return array(
            array('id, weight, login', 'required'),
            array('id, login', 'unique'),
//            array('id, weight', 'integerOnly' => true),
        );
    }

}