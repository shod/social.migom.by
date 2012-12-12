<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<title><?= $this->title ?></title>
    <?php
        Yii::app()->getClientScript()->registerCssFile('/css/default.css');
        Yii::app()->clientScript->registerScriptFile('/js/default.js');
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
    ?>
	<!--[if lt IE 9]>
	<script  src="html5fix.js"></script>
	<![endif]-->

</head>
<body>
    <div class="boby-container">

<!--<div class="wrapper_banner"> </div>-->

<?php CController::widget('Header'); ?>

<div class="wrapper_content">

    <?php CController::widget('HeaderNavigation'); ?>

    <?= $content ?>
</div>

<?php CController::widget('Footer'); ?>
    </div>
</body>
</html>