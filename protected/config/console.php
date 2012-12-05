<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'import' => array(
        'application.models.*',
        'application.services.*',
        'application.models.mongo.*',
        'application.components.*',
        'application.components.ConsoleCommand',
        'application.modules.api.components.*',
        'ext.yiiRestSuite.*',
        'ext.YiiMongoDbSuite.*',
        'ext.YiiMongoDbSuite.extra.*',
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Social Migom By Console',
    'modules' => array(
        'api' => array(
            'keys' => array('devel' => '86.57.245.247',
                'social' => '178.172.181.139',
                'migom' => '178.172.181.139',
//                                    'test' => '127.0.0.1'
            )
        ),
    ),
    'components' => array(
        'migom' => array(
            'class' => 'ERestServer',
            'connectionString' => 'http://test3.migom.by/api/api',
            'password' => 'social',
//                            'http_auth' => true,
//                            'http_user' => true,
//                            'http_pass' => true,
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=test4migomby',
            'emulatePrepare' => true,
            'username' => 'test4migomby',
            'password' => 'ET7jS8zcoAKT',
            'charset' => 'utf8',
        ),
        'mailer' => array(
            'class' => 'core.extensions.mailer.EMailer',
            'pathViews' => 'core.extensions.mailer.views.email',
            'pathLayouts' => 'core.extensions.mailer.views.email.layouts',
//                    'Host'          => 'SMTP HOST',
//                    'SMTPAuth'      => true,
//                    'Username'      => 'yourname@163.com',
//                    'Password'      => 'yourpassword',
//                    'From'          => 'support@migom.by',
        ),
        'mongodb' => array(
            'class' => 'EMongoDB',
            'connectionString' => 'mongodb://localhost',
            'dbName' => 'smigom',
            'fsyncFlag' => false,
            'safeFlag' => false,
            'useCursor' => false
        ),
        'RESTClient' => array(
            'class' => 'application.extensions.RESTClient.RESTClient',
            'servers' => array(
                'migom' => array(
                    'server' => 'http://test3.migom.by/api/api',
//                            'http_auth' => true,
//                            'http_user' => true,
//                            'http_pass' => true,
                    'key' => 'social',//'devel',
                ),
            ),
        ),
        'cache' => array(
            'class' => 'system.caching.CMemCache',
            //                    'useMemcached' => false,
            'keyPrefix' => 'a1e7e8ff',
            'servers' => array(
                array(
                    'host' => '178.172.181.139',
                    'port' => 11211,
                ),
                array(
                    'host' => 'localhost',
                    'port' => 11211,
                ),
            ),
        ),
    ),
    'params' => array(
        'mail' => array(
            'time_limit' => 50 // время отработки воркера (обновление в кроне - раз в минуту)
        ),
        'likes' => array(
            'time_limit' => 60 * 9 // 9 минут на обновление лайков (обновление в кроне - раз в 10 минут)
        ),
    ),

);