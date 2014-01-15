<?php

return array(
        'class' => 'CCacheHttpSession',
        'cacheID' => 'cache',
		'cookieParams' => array('domain' => 'www.social.migom.by'),
        'timeout' => 3600 * 24 * 30,
        'autoStart' => true,
		'sessionName' => 'PHPMIGOMSESSION',
        //'cookieMode' => 'only',
    )
?>