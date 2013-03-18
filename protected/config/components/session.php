<?php

return array(
        'class' => 'CCacheHttpSession',
        'cacheID' => 'cache',
        'cookieParams' => array(
					'domain' => '.migom.by', 
					"lifetime"=>3600 * 24 * 30
				),
        'timeout' => 3600 * 24 * 30,
        'autoStart' => true,
        //'cookieMode' => 'only',
    )
?>
