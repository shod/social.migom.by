<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<title> </title>
    <?php
        Yii::app()->getClientScript()->registerCssFile('/css/default.css');
        Yii::app()->getClientScript()->registerCssFile('/css/reset.css');
        Yii::app()->getClientScript()->registerCssFile('/css/styles.css');
        Yii::app()->clientScript->registerScriptFile('/js/default.js');
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
    ?>
	<link rel="stylesheet" type="text/css" href="reset.css" />
	<link rel="stylesheet" type="text/css" href="styles.css" />

	<!--[if lt IE 9]>
	<script  src="html5fix.js"></script>
	<![endif]-->

	<script  src="jquery.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			if($(window).width() < 1040){
				$(".wrapper_content").addClass("wrapper_centered_1000px");
				$(".wrapper_head").addClass("wrapper_centered_1000px");
			}
		});
		$(window).resize(function(){
			if($(document).width() < 1040){
				$(".wrapper_content").addClass("wrapper_centered_1000px");
				$(".wrapper_head").addClass("wrapper_centered_1000px");
			}
			else if($(document).width() > 1040){
				$(".wrapper_content").removeClass("wrapper_centered_1000px");
				$(".wrapper_head").removeClass("wrapper_centered_1000px");
			}
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$(".widget_hasPopup").mouseover(function () {
			         $(this).addClass("widget_opened");
			});
			$(".widget_hasPopup").mouseout(function () {
			         $(this).removeClass("widget_opened");
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$(".main-menu .main-nav_catalog div").mouseover(function () {
				$(".popup-menu_wrapper").addClass("displayBlock");
			});
			$(".popup-menu_wrapper").mouseover(function () {
				$(".popup-menu_wrapper").addClass("displayBlock");
			});
			$(".popup-menu_wrapper").mouseout(function () {
				if($(".popup-menu_levelTwo").css("display")=="none") $(".popup-menu_wrapper").removeClass("displayBlock");
			});
		});
	</script>

	<script type="text/javascript">
		jQuery(window).scroll(function() {
			if(jQuery(window).scrollTop() > 60){
				$(".wrapper_head").addClass("wrapper_head_fixed");
				$("body").addClass("body-paddingTop");
			}
			else {
				$(".wrapper_head").removeClass("wrapper_head_fixed");
				$("body").removeClass("body-paddingTop");
			};
		});
	</script>
</head>
<body>

<div class="wrapper_banner"> </div>

<?php CController::widget('Header'); ?>

<div class="wrapper_content">

    <?php CController::widget('HeaderNavigation'); ?>

    <?= $content ?>
</div>


</body>
</html>