<?php
/* @var $this ExpertsInController */
/* @var $model ExpertsIn */

$this->breadcrumbs=array(
	'Experts Ins'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ExpertsIn', 'url'=>array('index')),
	array('label'=>'Create ExpertsIn', 'url'=>array('create')),
	array('label'=>'View ExpertsIn', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ExpertsIn', 'url'=>array('admin')),
);
?>

<h1>Update ExpertsIn <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>