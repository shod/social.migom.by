<?php

class Likes_Comments_Product extends Likes
{

    /**
     * This method have to be defined in every Model
     * @return string MongoDB collection name, witch will be used to store documents of this model
     */
    public function getCollectionName()
    {
        return 'likes_comments_product';
    }

    /**
     * This method have to be defined in every model, like with normal CActiveRecord
     */
    public static function model($className = 'Comments_Product')
    {
        return parent::model($className);
    }

}