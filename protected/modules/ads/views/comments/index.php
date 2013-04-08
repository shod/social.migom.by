<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Комментарии'
);

//$this->menu=array(
//	array('label'=>'Добавить пользователя', 'url'=>array('create')),
//);

?>

<h1>Управление комментариями</h1>

<ul>
	<li><?= CHtml::link('Новости', array('comments/news')); ?></li>
	<li><?= CHtml::link('Обзоры', array('comments/article')); ?></li>
	<li><?= CHtml::link('Продукты', array('comments/product')); ?></li>
</ul>