<?php

return array(
        'class' => 'CLogRouter',
        'routes' => array(
            array(
                'class' => 'core.components.loging.QFileLogRoute',
                'levels' => 'error, warning, info, api, mysql',
                'enabled' => true,
            ),
//            array(
//                'class'=>'CEmailLogRoute',
//                'levels'=>'error, warning, info, api',
//                'emails'=>array('evgeniy.kazak@gmail.com'),
//            ),
//            array(
//                    'class' => 'CProfileLogRoute',
//                    'levels' => 'error, warning',
//                    'enabled' => true,
//            ),
//            array( // configuration for the toolbar
//                    'class' => 'XWebDebugRouter',
//                    'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
//                    'levels' => 'trace, info, profile, error, warning',
//                    'allowedIPs' => array('86.57.245.247','::1', '127.0.0.1'),
//            ),
        ),
    )
?>
