<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.models.mongo.*',
        'application.components.ConsoleCommand',
        'application.modules.api.components.*',
        'application.extensions.RESTClient.*',
        'ext.YiiMongoDbSuite.*',
        'ext.YiiMongoDbSuite.extra.*',
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Social Migom By Console',
    'modules' => array(
        'api' => array(
            'keys' => array('devel' => '86.57.245.247',
                'test3migomby' => '178.172.181.139',
                'migom' => '178.172.181.139',
//                                    'test' => '127.0.0.1'
            )
        ),
    ),
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=test4migomby',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
        'mailer' => array(
            'class' => 'application.extensions.mailer.EMailer',
            'pathViews' => 'application.views.email',
            'pathLayouts' => 'application.views.email.layouts',
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
                    'key' => 'devel',//'social',
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