<?php

// change the following paths if necessary
$yii    = dirname(__FILE__) . '/../framework/yii.php';
$yiiEx  = dirname(__FILE__) . '/protected/YiiBaseEx.php';
$config = dirname(__FILE__) . '/protected/config/main.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

if (YII_DEBUG === true) {
    include_once 'functions.php';
}
require_once($yii);
require_once($yiiEx);
Yii::getLogger()->autoDump = true;
Yii::getLogger()->autoFlush=1;
$yii = Yii::createWebApplication($config);
spl_autoload_unregister(array('YiiBase', 'autoload'));
spl_autoload_register(array('YiiBaseEx', 'autoload'));
$yii->run();
