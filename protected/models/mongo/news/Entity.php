<?php
class News_Entity extends EMongoEmbeddedDocument
{
    
    public $id;
    public $user_id;
    public $name;
    public $text;
    public $created_at;
    public $template;
    public $filter;

    
    public $params;
    
    public function embeddedDocuments() {  // встроенные, суб массивы!
        return array(
            // property name => embedded document class name
            'comment' => 'News_Entity_Comment',
            'likes' => 'News_Entity_Likes',
            'dislikes' => 'News_Entity_Likes',
            'like' => 'News_Entity_Like',
        );
    }
    
    // We can define rules for fields, just like in normal CModel/CActiveRecord classes
    public function rules()
    {
        return array(
            array('id, name, template, text, filter', 'required'),
        );
    }
    
}