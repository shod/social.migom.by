<?php
class Comments_Article extends Comments
{	
    public static function model($className = 'Article')
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'article_comments';
    }
    
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'parent' => array(self::BELONGS_TO, 'Comments_Article', 'parent_id'),
        );
    }
}