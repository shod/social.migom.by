<?php

class Likes_Comments_Article extends Likes
{

    /**
     * This method have to be defined in every Model
     * @return string MongoDB collection name, witch will be used to store documents of this model
     */
    public function getCollectionName()
    {
        return 'likes_comments_article';
    }

    /**
     * This method have to be defined in every model, like with normal CActiveRecord
     */
    public static function model($className = 'Comments_Article')
    {
        return parent::model($className);
    }

}