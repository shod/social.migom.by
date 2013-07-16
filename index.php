<?php
/*if(!isset($_GET['build'])){
	die('������� ������');
}*/
defined('YII_DEBUG') or define('YII_DEBUG',true);
if(isset($_GET['debug']) && $_GET['debug'] == 777){
	define('YII_DEBUG',true);
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$yiiEx  = dirname(__FILE__) . '/../core/YiiBaseEx.php';
$config=dirname(__FILE__).'/protected/config/local.php';

// remove the following lines when in production mode

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

if(YII_DEBUG === true){
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);

}
    include_once '../core/functions.php';

require_once($yii);
require_once($yiiEx);
Yii::getLogger()->autoDump = true;
Yii::getLogger()->autoFlush=1;
Yii::setPathOfAlias("core", dirname(__FILE__).'/../core_ek');
$yii = Yii::createWebApplication($config);
spl_autoload_unregister(array('YiiBase', 'autoload'));
spl_autoload_register(array('YiiBaseEx', 'autoload'));
$yii->run();