<?php

return array(
    // uncomment the following to enable the Gii tool
    'gii' => array(
        'class' => 'system.gii.GiiModule',
        'password' => 'pass',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        'ipFilters' => array(
            '86.57.245.247',
            '::1',
            '127.0.0.1'),
    ),
    'api' => array(
        'keys' => array(
            'devel' 		=> '86.57.245.247',
            'test3migomby' 	=> '178.172.181.139',
            'migom' 		=> '93.125.53.103',
            'test' 			=> '127.0.0.1',
			'testmigomby' 	=> '178.172.148.134',
			'migomby' => '178.172.148.134',
			'migomby2' => '178.172.148.109',
			'migomby3' => '91.149.189.197',
			'devmigomby' 	=> '178.172.181.139',
			'test2migomby' 	=> '93.125.53.104',
			'yamamigomby'	=> '93.125.53.104',
        )
    ),
    'ads',
	'messages',
);
?>
