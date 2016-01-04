<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <META http-equiv="Content-Language" CONTENT="RU">
	<link rel="SHORTCUT ICON" href="http://static.migom.by/img/favicon.ico">
    <meta http-equiv="Pragma" content="no-cache" />

    <title>Добро пожаловать</title>
    <link rel="stylesheet" type="text/css" href="css/default.css">
    <link rel="SHORTCUT ICON" href="/favicon.ico">

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.form.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>

    <link rel="stylesheet" type="text/css" media="all" href="css/chosen.css" />
    <script type="text/javascript" src="js/chosen.jquery.min.js"></script>

</head>

<body>
<div class="darker png_scale" style="display: none;"></div>

<div class="main">
    <div class="header">
        <? $this->block('top') ?>
    </div>

    <div class="outer">

        <div class="container">
            <div class="content">
                <div class="intend">
                    <?= $content ?>
                </div>
            </div><!--/content-->
        </div><!--/container-->

        <div class="sidebar">
            <? $this->block('left') ?>
        </div><!--/sidebar-->

    </div><!--/outer-->

</div>
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
<div class="footer">
    <div class="copy">&copy; 2006&mdash;<?= date('Y') ?> Migom.by&nbsp;<strong>Минск</strong>, <strong>Беларусь</strong>&nbsp;&nbsp;</div>
</div>
</body>
</html>