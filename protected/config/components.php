<?php

return array(
    'db' => array(
        'connectionString' => 'mysql:host=localhost;dbname=test4migomby',
        'emulatePrepare' => true,
        'username' => 'test4migomby',
        'password' => 'ET7jS8zcoAKT',
        'charset' => 'utf8',
    ),
    'mongodb' => array(
        'class' => 'EMongoDB',
        'connectionString' => 'mongodb://localhost',
        'dbName' => 'smigom',
        'fsyncFlag' => false,
        'safeFlag' => false,
        'useCursor' => false
    ),
    'image' => array(
        'class' => 'application.extensions.image.CImageComponent',
        // GD or ImageMagick
        'driver' => 'GD',
        // ImageMagick setup path
        'params' => array('directory' => '/opt/local/bin'),
    ),
    'RESTClient' => array(
        'class' => 'application.extensions.RESTClient.RESTClient',
        'servers' => array(
            'migom' => array(
                'server' => 'http://test3.migom.by/api/api',
//                            'http_auth' => true,
//                            'http_user' => true,
//                            'http_pass' => true,
                'key' => 'devel',
            ),
        ),
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

    'session' => array(
        'class' => 'CCacheHttpSession',
        'cacheID' => 'cache',
        'cookieParams' => array('domain' => '.migom.by'),
        'timeout' => 3600 * 24 * 30,
        'autoStart' => true,
        'cookieMode' => 'only',
    ),
    'cache' => array(
        'class' => 'system.caching.CMemCache',
        'keyPrefix' => 'a1e7e8ff',
        'servers' => array(
            array(
                'host' => '178.172.181.139',
                'port' => 11211,
            ),
        ),
    ),
    'errorHandler' => array(
        // use 'site/error' action to display errors
        'errorAction' => 'site/error',
    ),
    'log' => array(
        'class' => 'CLogRouter',
        'routes' => array(
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning, info, api',
                'enabled' => true,
            ),
//                array(
//                    'class'=>'CEmailLogRoute',
//                    'levels'=>'error, warning',
//                    'emails'=>array('schevgeny@gmail.com'),
//                ),
//                array(
//                        'class' => 'CProfileLogRoute',
//                        'levels' => 'error, warning',
//                        'enabled' => true,
//                ),
//                                array( // configuration for the toolbar
//                                        'class' => 'XWebDebugRouter',
//                                        'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
//                                        'levels' => 'trace, info, profile, error, warning',
//                                        'allowedIPs' => array('86.57.245.247','::1', '127.0.0.1'),
//                                ),
        ),
    ),
    'loid' => array(
        'class' => 'ext.lightopenid.loid',
    ),
    'eauth' => require(dirname(__FILE__) . '/components/eauth.php'),
);
?>
