<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/../../../core/config/main.php'),
	array(
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'runtimePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'
        . DIRECTORY_SEPARATOR . 'runtime',
        'name' => 'Social Migom BY',
        // preloading 'log' component
        'preload' => array('log'),
        'defaultController' => 'site',
        'sourceLanguage' => 'ru_RU',
        'language' => 'ru',
        'components' => require(dirname(__FILE__) . '/components.php'),
	)
);
