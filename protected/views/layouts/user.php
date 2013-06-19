<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<title><?= $this->title ?></title>
	<link rel="SHORTCUT ICON" href="http://static.migom.by/img/favicon.ico">
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
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1785065-12']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    <div class="boby-container">

<div class="wrapper_banner">
<div class="ad ad01" style="margin-top:5px;">
<NOINDEX>
<script type='text/javascript'><!--//<![CDATA[ 
   var m3_u = (location.protocol=='https:'?'https://adv.migom.by/open_ads/www/delivery/ajs.php':'http://adv.migom.by/open_ads/www/delivery/ajs.php');
   var m3_r = Math.floor(Math.random()*99999999999);
   var v_cid = '$vars[catalog_id]';
   if (!document.MAX_used) document.MAX_used = ',';
   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
   document.write ("?zoneid=13");
   document.write ('&amp;cb=' + m3_r);
   document.write ('&amp;cid=$vars[catalog_id]');           
   document.write ('&amp;url=$vars[url]');
   document.write ('&amp;country=$vars[country]');
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write ('&amp;charset=utf-8');
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script>       
</NOINDEX> 
</div>
</div>

<?php CController::widget('Header'); ?>

<div class="wrapper_content">

    <?php CController::widget('HeaderNavigation'); ?>
	<?php //Widget::get('Breadcrumbs')->html(); ?>
    <?= $content ?>
	
</div>
<?php CController::widget('Footer'); ?>
    </div>
	
<?php Widget::get('listener')->html(); ?>
</body>
</html>