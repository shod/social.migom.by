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

    public static function uploadAvatar($user_id, $file, $fileName = 'avatar'){
        $result = array('success' => false, 'error' => 'undefined');
        try {
            if(is_array($file)){
                $image = Yii::app()->image->load($file['avatar']);
            } else {
                $image = Yii::app()->image->load($file);
            }
            $image->resize(150, 150, Image::AUTO)->quality(75);
            $path = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..';
            $destination = $path . Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $user_id;
            @mkdir($destination, 0777, true);
            $image->save($destination . DIRECTORY_SEPARATOR. $fileName . '.jpg');
            $result = array('success' => true, 'error' => 'undefined');
        } catch (Exception $exc) {
            $result = array('success' => false, 'error' => $exc->getMessage());
        }
        return $result;
    }

    public static function cropAvatar($user_id, $file, $x, $y, $fileName = 'avatar'){
        $result = array('success' => false, 'error' => 'undefined');
        try {
            if(is_array($file)){
                $image = Yii::app()->image->load($file['avatar']);
            } else {
                $image = Yii::app()->image->load($file);
            }
            $image->resize($x, $y, Image::MIN)->crop($x, $y)->quality(75);
            $path = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..';
            $destination = $path . Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $user_id;
            @mkdir($destination, 0777, true);
            $file = $destination . DIRECTORY_SEPARATOR. $fileName . '_' . $x . 'x' . $y . '.jpg';
            $image->save($file);
            $result = array('success' => true, 'error' => 'undefined', 'file' => $file);
        } catch (Exception $exc) {
            $result = array('success' => false, 'error' => $exc->getMessage());
        }
        return $result;
    }

    public static function printAvatar($id, $login, $size = 50, $link = true){
        if($link){
            return CHtml::link(
                CHtml::image(Yii::app()->getBaseUrl().'/images/users/'.$id.'/avatar_' . $size . 'x' . $size . '.jpg', $login),
                    ($id != Yii::app()->user->id) ? array('/user/profile', 'id' => $id) : array('/user/profile')

            );
        } else {
            return CHtml::image(Yii::app()->getBaseUrl().'/images/users/'.$id.'/avatar_' . $size . 'x' . $size . '.jpg', $login);
        }

    }

    public static function uploadAvatarFromEmail($user_id, $email = null){
        $gravatarHash = (!empty($email))? $email:  rand(0, 99999999);
        $gravatarHash = md5( strtolower( trim( $gravatarHash ) ) );

        $result = UserService::uploadAvatarFromService($user_id,
                                'http://www.gravatar.com/avatar/'. $gravatarHash .'.jpg?d=identicon');
        return $result;
    }

    public static function clearTempAvatars($id){
        $path = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..';
        $destination = $path . Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $id;
        foreach(glob($destination."/*.jpg") as $file){
            if(strpos($file, 'avatar.jpg') === false){
                unlink($file);
            }
        }
    }
}
