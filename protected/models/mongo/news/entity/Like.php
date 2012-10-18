<?php
class News_Entity_Comment extends EMongoEmbeddedDocument
{
    
    public $user_id;
    public $weight;
    public $create_at;
    public $login;
    
    public function embeddedDocuments() {  // встроенные, суб массивы!
        return array(
            // property name => embedded document class name
        );
    }
    
    // We can define rules for fields, just like in normal CModel/CActiveRecord classes
    public function rules()
    {
        return array(
            array('user_id, create_at, weight, login', 'required'),
        );
    }
}