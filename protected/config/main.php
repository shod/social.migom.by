<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'runtimePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'
    . DIRECTORY_SEPARATOR . 'runtime',
    'name' => 'Social Migom BY',
    // preloading 'log' component
    'preload' => array('log'),
    'defaultController' => 'site',
    'sourceLanguage' => 'ru_RU',
    'language' => 'ru',
    // autoloading model and component classes
    'import' => array(
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
    'modules' => require(dirname(__FILE__) . '/modules.php'),
    'components' => require(dirname(__FILE__) . '/components.php'),
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
