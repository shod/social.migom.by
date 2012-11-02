<?php

/**
 * An example of extending the provider class.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
require_once dirname(dirname(__FILE__)) . '/services/GoogleOAuthService.php';

class CustomGoogleAuthService extends GoogleOAuthService
{

    //protected $jsArguments = array('popup' => array('width' => 450, 'height' => 450));

    protected $scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';

    protected function fetchAttributes()
    {
        $info = (array) $this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo');

        $this->attributes['soc_id'] = $info['id'];
        $this->attributes['login']  = $info['given_name'];
        if(isset($info['email'])){
            $this->attributes['email']  = $info['email'];
        }
        if(isset($info['picture'])){
            $this->attributes['avatar'] = $info['picture'];
        }
        if (isset($info['gender'])) {
            $this->attributes['sex'] = ($info['gender'] == 'male') ? 1 : 2;
        }
        if (isset($info['given_name'])) {
            $this->attributes['name'] = $info['given_name'];
        }
        if (isset($info['family_name'])) {
            $this->attributes['surname'] = $info['family_name'];
        }
    }

}