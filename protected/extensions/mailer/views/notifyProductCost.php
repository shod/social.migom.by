<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta http-equiv="Content-Language" content="ru" /> 

        <title>Уведомление о снижении цены на '<?php echo $productName;?>'</title>
    </head>
    <body>
        <p>Здравствуйте!</p>
        <p>Спешим Вам сообщить, что <?= Yii::app()->dateFormatter->format('dd.mm.yyyy', $date); ?> цена на товар <a href="http://migom.by/<?php echo $productId;?>"><?php echo $productName;?></a> снизилась до уровня <?php echo $cost;?>$ или менее.</p>
        <p>Ознакомиться с ценам продавцов Вы можете на <a href="http://migom.by/<?php echo $productId;?>">странице данного товара</a> портала Migom.by.</p>
        <p>Обращаем Ваше внимание, что цена на товар в момент посещения Migom.by может не соответствовать цене на товар в момент, когда Вам было отправлено это уведомление.</p>
        <p>С уважением, <a href="http://www.migom.by">Migom.by</a>.</p>
    </body>
</html>