<?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'profileForm',
            'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
            'focus'=>array($model,'username'),
            'htmlOptions' => array(
                'enctype'=>'multipart/form-data'
            ),
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
)); ?>

<div class="row">
    <?php echo $form->label($model->profile,'name'); ?>
    <?php echo $form->textField($model->profile,'name', array(), true, true); ?>
    <?php echo $form->error($model->profile,'name', array(), true, true); ?>
</div>

<div class="row">
    <?php echo $form->label($model->profile,'surname', array(), true, true); ?>
    <?php echo $form->textField($model->profile,'surname', array(), true, true); ?>
    <?php echo $form->error($model->profile,'surname', array(), true, true); ?>
</div>

<div class="row">
    <?php echo $form->label($model->profile,'avatar', array(), true, true); ?>
    <?php echo $form->fileField($model->profile,'avatar', array(), true, true); ?>
    <?php if($model->profile->avatar): ?>
        <?php echo CHtml::image(Yii::app()->getBaseUrl().'/images/users/'.$model->id.'/avatar.jpg'); ?>
    <?php endif; ?>
    <?php echo $form->error($model->profile,'avatar', array(), true, true); ?>
</div>

<div class="row">
    <?php echo $form->label($model->profile,'city_id', array(), true, true); ?>
    <?php echo $form->textField($model->profile,'city_id', array(), true, true); ?>
    <?php echo $form->error($model->profile,'city_id', array(), true, true); ?>
</div>

<div class="row">
    <?php echo $form->label($model->profile,'sex', array(), true, true); ?>
    <?php echo $form->dropDownList($model->profile,'sex', Users_Profile::$sexs, array('empty' => '')); ?>
    <?php echo $form->error($model->profile,'sex', array(), true, true); ?>
</div>

<div class="row">
    <?php echo $form->label($model->profile,'birthday', array(), true, true); ?>
    <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model'=>$model->profile,
            'attribute'=>'birthday',
            // additional javascript options for the date picker plugin
            'options'=>array(
                'showAnim'=>'fold',
                'showButtonPanel'=>true,
                'autoSize'=>true,
                'dateFormat'=>'dd.mm.yy',
//                            'defaultDate'=>$model->profile->birthday,
             ),
        ));
    ?>
    <?php echo $form->error($model->profile,'birthday', array(), true, true); ?>
</div>

        <div class="submit">
                <table width=100%><tr>
                        <td><input type="image" src="/images/login_btn.gif" /></td>
                </table>
        </div>

<?php $this->endWidget(); ?>