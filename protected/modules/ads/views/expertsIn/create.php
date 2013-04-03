<?php
/* @var $this ExpertsInController */
/* @var $model ExpertsIn */

$this->breadcrumbs=array(
	'Пользователи'=>array('/ads/users/admin'),
	'Experts Ins',
);

$this->menu=array(
	array('label'=>'Manage ExpertsIn', 'url'=>array('admin')),
);
?>

<h1>Create ExpertsIn</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>