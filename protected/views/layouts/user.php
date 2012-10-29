<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="ru" />
        <title><?php echo $this->title; ?></title>
        <?php Yii::app()->getClientScript()->registerCssFile('/css/default.css');?>
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <!--
            FOR EXAMPLE

            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerCoreScript('jquery.ui');
            Yii::app()->clientScript->registerScriptFile('/js/yourscript.js');
            Yii::app()->getClientScript()->registerCssFile('/css/default.css');

        -->
        <link rel="SHORTCUT ICON" href="/favicon.ico">
</head>
<body>
    
    <!--HEAD-->		
    <?= $content ?>	
    
    <div class="aside">
            <a href="javascript:"><img src="/images/content/banner.jpg"></a>
    </div>
    
</body>
</html>