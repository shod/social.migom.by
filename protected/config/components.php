<?php

return array(
    'db'            => require(dirname(__FILE__) . '/components/db.php'),
    'mongodb'       => require(dirname(__FILE__) . '/components/mongodb.php'),
    'session'       => require(dirname(__FILE__) . '/components/session.php'),
    'eauth'         => require(dirname(__FILE__) . '/components/eauth.php'),
    'cache'         => require(dirname(__FILE__) . '/components/cache.php'),
    'widgetFactory' => require(dirname(__FILE__) . '/components/widgetFactory.php'),
    'migom'         => require(dirname(__FILE__) . '/components/migom.php'),
//    'log'           => require(dirname(__FILE__) . '/components/log.php'),
    'image' => array(
        'class' => 'core.extensions.image.CImageComponent',
        // GD or ImageMagick
        'driver' => 'GD',
        // ImageMagick setup path
        'params' => array('directory' => '/opt/local/bin'),
    ),
    'messages' => array(
        'class'=>'CPhpMessageSource',
        'basePath' => '../core/messages'
    ),
    'user' => array(
        // enable cookie-based authentication
        'allowAutoLogin' => true,
        'class' => 'WebUser',
        'loginUrl' => array('site/login'),
        'defaultRole' => 'guest',
    ),
    'authManager' => array(
        'class' => 'PhpAuthManager',
        'defaultRoles' => array('guest'),
    ),
    // uncomment the following to enable URLs in path-format
    'urlManager' => array(
        'urlFormat' => 'path',
        'showScriptName' => false,
        'rules' => array(
            'api' => 'api/default/index',
            'ads' => 'ads/default/index',
            'user/<id:\d+>' => 'user/index',
            'user' => 'user/index',
            'profile/<id:\d+>' => 'user/profile',
            'profile' => 'user/profile',
            'profile/edit' => 'user/edit',
            '' => 'user/index',
            '<action:(login|logout)>' => 'site/<action>',
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
        ),
    ),
    'request' => array(
        'class' => 'HttpRequest'
    ),
    'errorHandler' => array(
        // use 'site/error' action to display errors
        'errorAction' => 'site/error',
    ),
    'loid' => array(
        'class' => 'ext.lightopenid.loid',
    ),
);