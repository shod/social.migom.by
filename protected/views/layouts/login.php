<!doctype html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<link rel="SHORTCUT ICON" href="http://static.migom.by/img/favicon.ico">
	<?php
	Yii::app()->getClientScript()->registerCssFile('/css/default.css');
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCoreScript('jquery.ui');
	$assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('core.widgets.assets'));
    Yii::app()->getClientScript()->registerCssFile($assetUrl.'/css/tooltipError.css');
    Yii::app()->clientScript->registerScriptFile($assetUrl.'/js/tooltipError.js');
    ?>
</head>
<body>
    <?= $content ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter21165061 = new Ya.Metrika({id:21165061,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/21165061" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->	
</body>
</html>