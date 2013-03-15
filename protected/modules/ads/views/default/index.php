<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Панель управлений <b>Social.Migom.by</b> (Админка)</h1>

<h2>Общая статистика social.migom.by</h2>

<table class="span-10">
	<tr style="background: none repeat scroll 0 0 #F8F8F8;">
		<td><b>Комментарии к новостям</b></td>
		<td><?= CHtml::link($statistics['commentsNews'], array('/ads/comments/news')) ?></td>
	</tr>
	<tr style="background: none repeat scroll 0 0 #E5F1F4;">
		<td><b>Комментарии к обзорам</b></td>
		<td><?= CHtml::link($statistics['commentsArticle'], array('/ads/comments/article')) ?></td>
	</tr>
	<tr style="background: none repeat scroll 0 0 #F8F8F8;">
		<td><b>Пользователи(active)</b></td>
		<td><?= CHtml::link($statistics['usersActive'], array('/ads/users/admin?Users%5Bid%5D=&Users%5Blogin%5D=&Users%5Brole%5D=&Users%5Bemail%5D=&Users%5Bstatus%5D=1&Users_page=1&ajax=yw0')) ?></td>
	</tr>
	<tr style="background: none repeat scroll 0 0 #E5F1F4;">
		<td><b>Пользователи(unactive)</b></td>
		<td><?= CHtml::link($statistics['usersNoActive'], array('/ads/users/admin?Users%5Bid%5D=&Users%5Blogin%5D=&Users%5Brole%5D=&Users%5Bemail%5D=&Users%5Bstatus%5D=2&Users_page=1&ajax=yw0')) ?></td>
	</tr>
	<tr style="background: none repeat scroll 0 0 #F8F8F8;">
		<td><b>Личные сообщения</b></td>
		<td><?= $statistics['messages'] ?></td>
	</tr>
	<tr style="background: none repeat scroll 0 0 #E5F1F4;">
		<td><b>Нотификации о снижении цены</b></td>
		<td><?= CHtml::link($statistics['notifyProductCost'], array('/ads/notify/productcost')) ?></td>
	</tr>
	<tr style="background: none repeat scroll 0 0 #F8F8F8;">
		<td><b>Нотификации о появлении в продаже</b></td>
		<td><?= CHtml::link($statistics['notifyProduct'], array('/ads/notify/product')) ?></td>
	</tr>
	<tr style="background: none repeat scroll 0 0 #E5F1F4;">
		<td><b>Избранные товары</b></td>
		<td><?= CHtml::link($statistics['bookmark'], array('/ads/bookmark/admin')) ?></td>
	</tr>
<table>