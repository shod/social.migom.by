<?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'formLogin',
            'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
            'focus'=>array($model,'username'),
            'htmlOptions' => array('style' => 'margin-bottom: 20px; height: 100px'),
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
)); ?>

        <table width="100%">
            <tr>
                <td>
                        <h4><?= $model->getAttributeLabel('email') ?>:<span id="error_email"><?php echo $form->error($model,'email', array(), true, true); ?></span></h4>
                        <div class="inp"><?php echo $form->textField($model,'email'); ?></div>
                </td>
                <td>
                        <h4><?= $model->getAttributeLabel('password') ?>:<span id="error_pswd"><?php echo $form->error($model,'password', array(), true, true); ?></span></h4>
                        <div class="inp"><?php echo $form->passwordField($model,'password'); ?></div>
                </td>
            </tr>
        </table>
        <div class="submit">
                <table width=100%><tr>
                        <td><input type="image" src="/images/login_btn.gif" /></td>
                        <td valign="middle">
                                <div class="idle"><label><?php echo $form->checkBox($model,'rememberMe', array('name' => 'idle', 'value' => 1)); ?> <?= $model->getAttributeLabel('rememberMe') ?></label></div>
                        </td>
<!--                        <td valign="middle">
                            <?= CHtml::ajaxSubmitButton(Yii::t('Site', 'Восстановаить пароль'), CController::createUrl('/site/remindPass'), array('success' => 'function(data){var json = jQuery.parseJSON(data); if(json.success){$("#Form_Login_email_em_").show().html( "" + json.message);} else { $("#Form_Login_email_em_").show().html("" + json.Form_Login_email); }  return false;}')) ?>
                        </td>-->
                </table>
        </div>		

<?php $this->endWidget(); ?>