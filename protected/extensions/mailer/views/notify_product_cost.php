<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta http-equiv="Content-Language" content="ru" /> 

        <title>Уведомление о снижении цены на <?= $vars[name];?></title>
    </head>
    <body>
        <p>Здравствуйте!</p>
        <p>Спешим Вам сообщить, что <?= $vars[date];?> цена на товар <a href="<?=$vars[url];?>"><?= $vars[name];?></a> снизилась до уровня <?= $vars[cost]$;?> или менее.</p>
        <p>Ознакомиться с ценам продавцов Вы можете на <a href="<?= $vars[uri];?>">странице данного товара</a> портала Migom.by.</p>
        <p>Обращаем Ваше внимание, что цена на товар в момент посещения Migom.by может не соответствовать цене на товар в момент, когда Вам было отправлено это уведомление.</p>
        <p>С уважением, <a href="http://www.migom.by">Migom.by</a>.</p>
    </body>
</html>