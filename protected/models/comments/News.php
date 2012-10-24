<?php
class Comments_News extends Comments
{	
    public static function model($className = 'News')
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'news_comments';
    }
    
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'parent' => array(self::BELONGS_TO, 'Comments_News', 'parent_id'),
        );
    }
}