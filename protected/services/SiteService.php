<?php

class UserService {
    
    public static $images_mime = array('image/jpeg' => 'jpg', 'image/png' => 'png');
    
    public static function uploadAvatarFromService($user_id, $file_url){
        $imageSize = getimagesize($file_url);
        if($imageSize){
            $path = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..';
            $destination = $path . Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $user_id;
            @mkdir($destination, 0777, true);
            if(UserService::$images_mime[$imageSize['mime']] == 'png'){
                if(UserService::png2jpg($file_url, $destination . DIRECTORY_SEPARATOR . 'avatar.jpg', 75)){
                    return Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . 'avatar.jpg';
                }
            } else {
                if(copy($file_url, $destination . DIRECTORY_SEPARATOR . 'avatar.jpg')){
                    return Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . 'avatar.jpg';
                }
            }
            return false;
        }
    }
    
    public static function png2jpg($originalFile, $outputFile, $quality) {
        $image = imagecreatefrompng($originalFile);
        imagejpeg($image, $outputFile, $quality);
        imagedestroy($image);
        return true;
    }
    
    public static function uploadAvatar($user_id, $file){
        $result = array('success' => false, 'error' => 'undefined');
        try {
            $image = Yii::app()->image->load($file['avatar']);
            $image->resize(50, 50, Image::NONE)->quality(75);
            $path = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..';
            $destination = $path . Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $user_id;
            @mkdir($destination, 0777, true);
            $image->save($destination . DIRECTORY_SEPARATOR. 'avatar.jpg');
            $result = array('success' => true, 'error' => 'undefined');
        } catch (Exception $exc) {
            $result = array('success' => false, 'error' => $exc->getMessage());
        }
        return $result;
    }
    
    public static function printAvatar($id, $login){
        return CHtml::link(
            CHtml::image(Yii::app()->getBaseUrl().'/images/users/'.$id.'/avatar.jpg', $login, array('style' => 'width:50px; height:50px; border: 1px solid black', 'class' => 'avatar', 'border' => 0)),
            ($id != Yii::app()->user->id) ? array('/user/index', 'id' => $id) : array('/user/index')
            
        );
    }
    
    public static function uploadAvatarFromEmail($user_id, $email = null){
        $gravatarHash = (!empty($email))? $email:  rand(0, 99999999);
        $gravatarHash = md5( strtolower( trim( $gravatarHash ) ) );

        $result = UserService::uploadAvatarFromService($user_id, 
                                'http://www.gravatar.com/avatar/'. $gravatarHash .'.jpg?d=identicon');
        return $result;
    }
}
