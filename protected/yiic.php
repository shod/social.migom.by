<?php

// change the following paths if necessary
$config=dirname(__FILE__).'/config/console.php';

if (YII_DEBUG === true) {
    include_once '/../functions.php';
}
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once(dirname(__FILE__).'/../../framework/yii.php');
require_once(dirname(__FILE__).'/YiiBaseEx.php');

if(isset($config))
{
	$app=Yii::createConsoleApplication($config);
	$app->commandRunner->addCommands(YII_PATH.'/cli/commands');
}
else
	$app=Yii::createConsoleApplication(array('basePath'=>dirname(__FILE__).'/cli'));

spl_autoload_unregister(array('YiiBase', 'autoload'));
spl_autoload_register(array('YiiBaseEx', 'autoload'));

$env=@getenv('YII_CONSOLE_COMMANDS');
if(!empty($env))
	$app->commandRunner->addCommands($env);

$app->run();