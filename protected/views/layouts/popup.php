<!doctype html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<?php
    Yii::app()->getClientScript()->registerCssFile('/css/default.css');
    Yii::app()->clientScript->registerScriptFile('/js/default.js');
    Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCoreScript('jquery.ui');
    ?>
</head>
<body>

    <?= $content ?>

</body>
</html>