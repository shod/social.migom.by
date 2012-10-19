<?php
class News_Entity_Comment extends EMongoEmbeddedDocument
{
    
    public $user_id;
    public $text;
    public $created_at;
    public $login;
    
    public function embeddedDocuments() {  // встроенные, суб массивы!
        return array(
            // property name => embedded document class name
            'likes' => 'News_Entity_Likes',
            'dislikes' => 'News_Entity_Likes'
        );
    }
    
    // We can define rules for fields, just like in normal CModel/CActiveRecord classes
    public function rules()
    {
        return array(
            array('user_id, created_at, text, login', 'required'),
        );
    }
}