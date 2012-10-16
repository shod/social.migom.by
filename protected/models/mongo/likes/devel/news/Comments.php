<?php

class Likes_Devel_News_Comments extends Likes
{

    /**
     * This method have to be defined in every Model
     * @return string MongoDB collection name, witch will be used to store documents of this model
     */
    public function getCollectionName()
    {
        return 'likes_devel_news_comments';
    }

    /**
     * This method have to be defined in every model, like with normal CActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}