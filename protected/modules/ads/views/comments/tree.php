<p style="color:blue; font-size:16px;">
	<?php if(Yii::app()->request->getParam('model') == 'product'): ?>
		<?php $link = Yii::app()->params['migomBaseUrl'].'/'.$model->entity_id . '/discussion/?'; ?>
	<?php else: ?>
		<?php $link = Yii::app()->params['migomBaseUrl'].'/?'.Yii::app()->request->getParam('model').'_id='.$model->entity_id; ?>
	<?php endif; ?>
	<?= CHtml::link('LinkNew', $link); ?>
</p>
<?php if($model->parent): ?>
    <?php $this->renderPartial('popup/comment', array('model' => $model->parent)); ?>
<?php endif; ?>

<div class="form">
    
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comments-form',
	'enableAjaxValidation'=>false,
)); ?>
        <?php echo CHtml::hiddenField('approoveUrl', $this->createUrl('approove', array('model' => $modelTitle, 'id' => $model->id)), array('id' => 'approoveUrl')); ?>
        <?php echo CHtml::hiddenField('deleteUrl', $this->createUrl('delete', array('model' => $modelTitle, 'id' => $model->id)), array('id' => 'deleteUrl')); ?>
        <?php echo CHtml::hiddenField('saveUrl', $this->createUrl('save', array('model' => $modelTitle, 'id' => $model->id)), array('id' => 'saveUrl')); ?>
        <div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows' => 6, 'cols' => 66)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>
    
<?php $this->endWidget(); ?>

</div>
<input onclick="$.get('<?= $link.'&debug=5' ?>')" type="button" value="Обновить кэш" />