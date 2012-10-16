<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Social Migom BY',

	// preloading 'log' component
	'preload'=>array('log'),
        'defaultController' => 'site',
        'sourceLanguage'    =>'ru_RU',
        'language'          =>'ru_RU',

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
                'application.models.mongo.*',
		'application.components.*',
                'application.widgets.*',
		'application.extensions.RESTClient.*',
		'application.extensions.yiidebugtb.*',
		'application.services.*',
                'application.modules.api.components.*',
		'ext.eoauth.*',
		'ext.eoauth.lib.*',
		'ext.lightopenid.*',
		'ext.eauth.*',
		'ext.eauth.custom_services.*',

                'ext.YiiMongoDbSuite.*',
                'ext.YiiMongoDbSuite.extra.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'pass',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('86.57.245.247','::1', '127.0.0.1'),
		),
                'api' => array(
                    'keys' => array('devel'=>'86.57.245.247',
                                    'test3migomby' => '178.172.181.139',
                                    'migom' => '178.172.181.139',
//                                    'test' => '127.0.0.1'
                                    )
                ),
                'ads'=>array(
			'ipFilters'=>array('86.57.245.247','::1', '127.0.0.1'),
		),
	),

	// application components
	'components'=>array(
                'mongodb' => array(
                    'class'            => 'EMongoDB',
                    'connectionString' => 'mongodb://localhost',
                    'dbName'           => 'smigom',
                    'fsyncFlag'        => false,
                    'safeFlag'         => false,
                    'useCursor'        => false              
                ),
                'image'=>array(
                    'class'=>'application.extensions.image.CImageComponent',
                      // GD or ImageMagick
                      'driver'=>'GD',
                      // ImageMagick setup path
                      'params'=>array('directory'=>'/opt/local/bin'),
                 ),
                'RESTClient' => array(
                    'class' => 'application.extensions.RESTClient.RESTClient',
                    'servers' => array(
                        'migom' => array(
                            'server' => 'http://test4.migom.by/api',
//                            'http_auth' => true,
//                            'http_user' => true,
//                            'http_pass' => true,
                            'key' => 'devel',
                        ),
                    ),
                ),
            //todo вынести в модуль
                'ApiException'=>array(
                    'class'=>'ApiException',
                    'status'=>200,
                ),
                'user'=>array(
                    // enable cookie-based authentication
                    'allowAutoLogin'=>true,
                    'class' => 'WebUser',
                    'loginUrl'=>array('site/login'),
		),
                'authManager' => array(
                    // Будем использовать свой менеджер авторизации
                    'class' => 'PhpAuthManager',
                    // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
                    'defaultRoles' => array('guest'),
                ),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
                        'showScriptName' => false,
			'rules'=>array(
                                'api' => 'api/default/index',
                                'ads' => 'ads/default/index',
                                'user/<id:\d+>'=>'user/index',
                                'user'=>'user/index',
                                '' => 'user/index',
                                '<action:(login|logout)>'=>'site/<action>',
                                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			),
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=test4migomby',
			'emulatePrepare' => true,
			'username' => 'test4migomby',
			'password' => 'ET7jS8zcoAKT',
			'charset' => 'utf8',
		),
                'session' => array(
//            'sessionName' => 'migom.by',
                    'class' => 'CCacheHttpSession',
                    'cacheID' => 'cache',
                    'cookieParams' => array('domain' => '.migom.by'),
                    'timeout' => 60*60*8,
                    'autoStart' => true,
                    'cookieMode' => 'only',
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
                    ),
                ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
//		'log' => array(
//                        'class' => 'CLogRouter',
//                        'routes' => array(
//                                array(
//                                        'class' => 'CFileLogRoute',
//                                        'levels' => 'error, warning, info'
//                                ),				
//                                array( // configuration for the toolbar
//                                        'class' => 'XWebDebugRouter',
//                                        'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
//                                        'levels' => 'trace, info, profile, error, warning',
//                                        'allowedIPs' => array('86.57.245.247','::1', '127.0.0.1'),
//                                ),
//                        ),
//                ),
                'loid' => array(
			'class' => 'ext.lightopenid.loid',
		),
		'eauth' => array(
                    'class' => 'ext.eauth.EAuth',
                    'popup' => true, // Use the popup window instead of redirecting.
                    'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
                    'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
                    'services' => array( // You can change the providers and their classes.
//                        'google' => array(
//                            'class' => 'GoogleOpenIDService',
//                        ),
//                        'yandex' => array(
//                            'class' => 'YandexOpenIDService',
//                        ),
//                        'twitter' => array(
//                            // register your app here: https://dev.twitter.com/apps/new
//                            'class' => 'TwitterOAuthService',
//                            'key' => '...',
//                            'secret' => '...',
//                        ),
                        'google_oauth' => array(
                            // register your app here: https://code.google.com/apis/console/
                            'class' => 'CustomGoogleAuthService',
                            'client_id' => '601138882389-tkfndj73f4cnnjpuu402ihva57ndscfb.apps.googleusercontent.com',
                            'client_secret' => 'L_8-TQDdm31OEz9vXNfOWB8J',
                            'title' => 'Google (OAuth)',
                        ),
//                        'yandex_oauth' => array(
//                            // register your app here: https://oauth.yandex.ru/client/my
//                            'class' => 'YandexOAuthService',
//                            'client_id' => '...',
//                            'client_secret' => '...',
//                            'title' => 'Yandex (OAuth)',
//                        ),
                        'facebook' => array(
                            // register your app here: https://developers.facebook.com/apps/
                            'class' => 'CustomFacebookOAuthService',
                            'client_id' => '376588799076610',
                            'client_secret' => 'e48917e90c261a4ec630b20abddbe8e0',
                        ),
//                        'linkedin' => array(
//                            // register your app here: https://www.linkedin.com/secure/developer
//                            'class' => 'LinkedinOAuthService',
//                            'key' => '...',
//                            'secret' => '...',
//                        ),
//                        'github' => array(
//                            // register your app here: https://github.com/settings/applications
//                            'class' => 'GitHubOAuthService',
//                            'client_id' => '...',
//                            'client_secret' => '...',
//                        ),
//                        'live' => array(
//                            // register your app here: https://manage.dev.live.com/Applications/Index
//                            'class' => 'LiveOAuthService',
//                            'client_id' => '...',
//                            'client_secret' => '...',
//                        ),
                        'vkontakte' => array(
                            // register your app here: https://vk.com/editapp?act=create&site=1
                            'class' => 'CustomVKontakteOAuthService',
                            'client_id' => '3142907',
                            'client_secret' => '9b1FoGkG8u2Rtyi9mFC6',
                        ),
//                        'mailru' => array(
//                            // register your app here: http://api.mail.ru/sites/my/add
//                            'class' => 'MailruOAuthService',
//                            'client_id' => '...',
//                            'client_secret' => '...',
//                        ),
//                        'moikrug' => array(
//                            // register your app here: https://oauth.yandex.ru/client/my
//                            'class' => 'MoikrugOAuthService',
//                            'client_id' => '...',
//                            'client_secret' => '...',
//                        ),
//                        'odnoklassniki' => array(
//                            // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
//                            // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
//                            'class' => 'OdnoklassnikiOAuthService',
//                            'client_id' => '...',
//                            'client_public' => '...',
//                            'client_secret' => '...',
//                            'title' => 'Odnokl.',
//                        ),
                    ),
                ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
//	'params'=>array(
//		// this is used in contact page
//		'adminEmail'=>'webmaster@example.com',
//	),
            'onBeginRequest' => function($event) {
            $route = Yii::app()->getRequest()->getPathInfo();
            $module = substr($route, 0, strpos($route, '/'));

            if (Yii::app()->hasModule($module)) {
                $module = Yii::app()->getModule($module);
                if (isset($module->urlRules)) {
                    $urlManager = Yii::app()->getUrlManager();
                    $urlManager->addRules($module->urlRules);
                }
            }
            return true;
        },
);
