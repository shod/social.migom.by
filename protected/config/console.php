<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
            'import'=>array(
		'application.models.*',
                'application.models.mongo.*',
                'application.components.ConsoleCommand',
                'ext.YiiMongoDbSuite.*',
                'ext.YiiMongoDbSuite.extra.*',
            ),
            'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
            'name'=>'Social Migom By Console',
            'components'=>array(
                'db'=>array(
                    'connectionString' => 'mysql:host=localhost;dbname=test4migomby',
                    'emulatePrepare' => true,
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                ),  
                'mailer' => array(
                    'class'         => 'application.extensions.mailer.EMailer',
                    'pathViews'     => 'application.views.email',
                    'pathLayouts'   => 'application.views.email.layouts',
//                    'Host'          => 'SMTP HOST',
//                    'SMTPAuth'      => true,
//                    'Username'      => 'yourname@163.com',
//                    'Password'      => 'yourpassword',
//                    'From'          => 'support@migom.by',
                ),
                'mongodb' => array(
                    'class'            => 'EMongoDB',
                    'connectionString' => 'mongodb://localhost',
                    'dbName'           => 'smigom',
                    'fsyncFlag'        => false,
                    'safeFlag'         => false,
                    'useCursor'        => false              
                ),
            ),
            'params' => array(
                'mail' => array(
                        'time_limit' => 50 // время отработки воркера
                    )
            ),
);