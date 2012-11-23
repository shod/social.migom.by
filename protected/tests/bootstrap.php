<?php
$yiit = '/../../../framework/yiit.php';
$yiiEx  = dirname(__FILE__) . '/../YiiBaseEx.php';
$config = dirname(__FILE__) . '/../config/test.php';

require_once($yiit);
//require_once 'PHPUnit/Autoload.php';
require_once '/../../../framework/test/CTestCase.php';
Yii::setPathOfAlias("api", dirname(__FILE__).'/../modules/api');
require_once(dirname(__FILE__).'/WebTestCase.php');

$yiiEx  = dirname(__FILE__) . '/../YiiBaseEx.php';

function autoload($className){
    $className = ucfirst($className);
    return YiiBase::autoload($className);
}

require_once($yiiEx);

Yii::createWebApplication($config);
spl_autoload_unregister(array('YiiBase', 'autoload'));
spl_autoload_register(array('YiiBaseEx', 'autoload'));
