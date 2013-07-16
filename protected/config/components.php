<?php

return array(
    'db'            => require(dirname(__FILE__) . '/components/db.php'),
    'mongodb'       => require(dirname(__FILE__) . '/components/mongodb.php'),
    'session'       => require(dirname(__FILE__) . '/components/session.php'),
	'sessionCache'  => require(dirname(__FILE__) . '/components/session/cache.php'),
    'eauth'         => require(dirname(__FILE__) . '/components/eauth.php'),
    'cache'         => require(dirname(__FILE__) . '/components/cache.php'),
    'widgetFactory' => require(dirname(__FILE__) . '/components/widgetFactory.php'),
    'migom'         => require(dirname(__FILE__) . '/components/migom.php'),
	'tags'          => require(dirname(__FILE__) . '/components/tags.php'),
	'yama'          => require(dirname(__FILE__) . '/components/yama.php'),
    'log'           => require(dirname(__FILE__) . '/components/log.php'),
	'migom_job'           => require(dirname(__FILE__) . '/components/migom_job.php'),
	'notify' => array(
		'class' => 'core.components.QUserNotify',
	),
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
        'allowAutoLogin' => false,
        'class' => 'WebUser',
        'loginUrl' => array('login'),
        'defaultRole' => 'guest',
    ),
    'authManager' => array(
        'class' => 'PhpAuthManager',
        'defaultRoles' => array('guest'),
    ),
	/*'mailer' => array(
        'class' => 'core.extensions.mailer.EMailer',
        'pathViews' => 'core.extensions.mailer..email',
        'pathLayouts' => 'core.extensions.mailer.layouts',
                    'Host'          => 'smtp.gmail.com',
                    'SMTPAuth'      => true,
                    'Username'      => 'no_reply@migom.by',
                    'Password'      => 'hetu59GSw9',
					'SMTPSecure'	=> 'tls',
					'Port' 			=> 25,
        'From'   	=> 'no_reply@migom.by',
		'Sender' 	=> 'no_reply@migom.by',
		'CharSet' 	=> 'UTF-8',
		'Hostname' 	=> 'migom.by',
		
    ),*/
	
	'mailer' => array(
        'class' => 'core.extensions.mailer.EMailer',
        'pathViews' => 'core.extensions.mailer..email',
        'pathLayouts' => 'core.extensions.mailer.layouts',
                    'Host'          => 'smtp.mandrillapp.com',
                    'SMTPAuth'      => true,
                    'Username'      => 'promo@migom.by',
                    'Password'      => '0yAXDuun5BHJ1OnDPN4zFQ',
					'SMTPSecure'	=> 'tls',
					'Port' 			=> 587,
        'From'   	=> 'no_reply@migom.by',
		'Sender' 	=> 'no_reply@migom.by',
		'CharSet' 	=> 'UTF-8',
		'Hostname' 	=> 'migom.by',
		
    ),
    // uncomment the following to enable URLs in path-format
    'urlManager' => array(
        'urlFormat' => 'path',
        'showScriptName' => false,
        'rules' => array(
			'user/emailconfirm/<hash:\w{32}>' => '/user/emailconfirm',
            'api' => 'api/default/index',
            'ads' => 'ads/default/index',
			'gii'=>'gii',
            'gii/<controller:\w+>'=>'gii/<controller>',
            'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
            'user/<id:\d+>' => 'user/index',
            'user' => 'user/index',
            'profile/<id:\d+>' => 'user/profile',
			
			'site/default' => 'test/link',

            'profile' => 'user/profile',
            'profile/edit' => 'user/edit',
            '' => 'user/index',
			
			'messages' => 'messages/messages/index',
			'messages/<action:\w+>/<id:\d+>' => 'messages/messages/<action>',
			'messages/<action:\w+>' => 'messages/messages/<action>',
			
			'things' => 'things/index',
			'things/<action:\w+>/<id:\d+>' => 'things/<action>',
			'things/<action:\w+>' => 'things/<action>',
			
			'yama' => 'yama/index',
			'things/<action:\w+>/<id:\d+>' => 'yama/<action>',
			'things/<action:\w+>' => 'yama/<action>',

			'<action:(login|logout)>' => 'site/<action>',
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
			'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			'<url\w*>' => 'site/static',
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