<?php
class Comments_Adverts extends Comments
{	
    public static function model($className = 'Adverts')
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'adverts_comments';
    }
    
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'parent' => array(self::BELONGS_TO, 'Comments_Adverts', 'parent_id'),
        );
    }
}