<?php

$this->breadcrumbs=array(
	'Нотификации о снижении цены'
);

?>

<h1>Нотификации о снижении цены</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'ajaxUpdate' => true,
	'filter'=>$model,
    'cssFile'=>$this->module->assetsUrl.'/css/styles-admin.css',
	'columns'=>array(
		array(
			'name' => 'userName',
			'type' => 'html',
			'value' => 'CHtml::link($data->user->email, array("/user/".$data->user->id))',
		),
		'user.fullname',
		array(
			'name' => 'product_id',
			'type' => 'html',
			'value' => 'CHtml::link($data->product_id, Yii::app()->params["migomBaseUrl"] . "/" . $data->product_id)',
		),
		'cost',
		array(
			'name' => 'created_at',
			'filter' =>false,
			'value' => 'SiteService::timeToDate($data->created_at)',
		),
		array(
			'name' => 'countIds',
			'type' => 'raw',
			'filter' =>false,
		),
		array(
			'value'=>'',
			'filter'=>CHtml::activeDropDownList($model, 'groupGrid',  // you have to declare the selected value
                array(
                    ''=>'',
                    'user.email'=>'User',
                    'product_id'=>'Product',
                )
            ),
			'htmlOptions' => array('width' => '40px', 'height' => '40px'),
		),
	),
)); ?>
