<?php

class Likes_Migom_Comments_News extends Likes
{

    /**
     * This method have to be defined in every Model
     * @return string MongoDB collection name, witch will be used to store documents of this model
     */
    public function getCollectionName()
    {
        return 'likes_migom_comments_news';
    }

    /**
     * This method have to be defined in every model, like with normal CActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}