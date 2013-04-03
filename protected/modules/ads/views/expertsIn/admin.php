<?php
/* @var $this ExpertsInController */
/* @var $model ExpertsIn */

$this->breadcrumbs=array(
	'Experts Ins'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create ExpertsIn', 'url'=>array('create')),
);

?>

<h1>Manage Experts Ins</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'experts-in-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		array(
			'class'=>'CButtonColumn',
            'template' => '{update} {delete}',
		),
	),
)); ?>
