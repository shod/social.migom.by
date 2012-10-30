<div class="lenta">

    <?php $this->widget('UserMain', array('model' => $model, 'active' => 'profile')); ?>

    <div class="main profile">
        <div class="summary">
            <div class="avatar">
                <?php $this->widget('application.extensions.EAjaxUpload.EAjaxUpload',
                array(
                        'id'=>'uploadFile',
                        'config'=>array(
                            'action'=>  $this->createUrl('uploadAvatar'),
                            'allowedExtensions'=>array("jpg","jpeg","png"),//array("jpg","jpeg","gif","exe","mov" and etc...
                            'sizeLimit'=> 2 *1024 * 1024,// maximum file size in bytes
                //                'minSizeLimit'=>10*1024*1024,// minimum file size in bytes
                            'onComplete'=>"js:function(id, fileName, responseJSON){ imageSrc = $('#uploadAvatar a img').attr('src'); $('#uploadAvatar a img').attr('src', imageSrc + '?' + responseJSON); }",
                            'template' => '<div class="qq-uploader">
                                    <div class="qq-upload-drop-area"><span>' . Yii::t('Profile', 'Перетащите файл сюда') . '</span></div>
                                    <div id="uploadAvatar" class="qq-upload-button">' . UserService::printAvatar($model->id, $model->login, 96) . '</div>
                                    <ul class="qq-upload-list"></ul>
                                </div>',
                            'messages'=>array(
                                    'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
                                    'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
                                    'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
                                    'emptyError'=>"{file} is empty, please select files again without it.",
                                    'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
                                ),
                            )
                ));
                ?>
            </div>
            <!--<div class="avatar"><?= UserService::printAvatar($model->id, $model->login, 96); ?></div>-->
            <div class="name">
                <strong><?= CHtml::link($model->login, array('/user/profile', 'id' => $model->id)); ?></strong>
                <?= CHtml::link(Yii::t('Profile', 'назад (отмена)'), array('/profile')) ?>
            </div>
        </div>
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
        <table>
            <caption><?= Yii::t('Profile', 'Редактирование профиля') ?></caption>
            <tr>
                <th><?php echo $form->label($model->profile,'name'); ?>:</th>
                <td><?php echo $form->textField($model->profile,'name'); ?></td>
            </tr>
            <tr>
                <th><?php echo $form->label($model->profile,'surname'); ?>:</th>
                <td><?php echo $form->textField($model->profile,'surname'); ?></td>
            </tr>
            <tr>
                <th><?php echo $form->label($model->profile,'sex'); ?>:</th>
                <td>
                    <label>
                        <input type="radio" name="sex" value="1" <?php if($model->profile->sex == 1): ?>checked="checked" <?php endif;?>>
                        <span><?= Yii::t('Profile', 'мужской'); ?></span>
                    </label>
                    <label>
                        <input type="radio" value="2" name="sex" <?php if($model->profile->sex == 2): ?>checked="checked" <?php endif;?>>
                        <span><?= Yii::t('Profile', 'женский'); ?></span>
                    </label>
                </td>
            </tr>
            <tr class="birth">
                <th><?php echo $form->label($model->profile,'birthday'); ?>:</th>
                <td>
                    <?php $birthday = explode('.', $model->profile->birthday); ?>
                    <?= CHtml::dropDownList('birthday[day]', round(array_shift($birthday)), $days, array('class' => 'day')) ?>
                    <?= CHtml::dropDownList('birthday[month]', array_shift($birthday), $month, array('class' => 'month')) ?>
                    <?= CHtml::dropDownList('birthday[year]', array_shift($birthday), $year, array('class' => 'year')) ?>
<!--                    <label>
                        <input type="checkbox" checked="checked">
                        <span>скрывать дату рождения</span>
                    </label>-->
                </td>
            </tr>
            <tr>
                <th><?php echo $form->label($model->profile,'country'); ?>:</th>
                <td><?php echo $form->textField($model->profile,'country'); ?></td>
            </tr>
            <tr>
                <th><?php echo $form->label($model->profile,'city_id'); ?>:</th>
                <td><?php echo $form->textField($model->profile,'city_id'); ?></td>
            </tr>
            <?php if(!$model->email): ?>
                <tr>
                    <th><?php echo $form->label($model,'email'); ?>:</th>
                    <td><?php echo $form->textField($model,'email'); ?><?php echo $form->error($model,'email'); ?></td>

                </tr>
                <tr>
                    <th><?php echo $form->label($model,'reemail'); ?>:</th>
                    <td><?php echo $form->textField($model,'reemail'); ?><?php echo $form->error($model,'reemail'); ?></td>

                </tr>
            <?php endif; ?>

        </table>

<!--        <table class="collapsible">
            <caption><p><span>Настройки ленты</span></p></caption>
            <tr>
                <th></th>
                <td>
                    <label>
                        <input type="checkbox" checked="checked">
                        <span>Новые комментарии</span>
                    </label>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <label>
                        <input type="checkbox" checked="checked">
                        <span>Снижение цены</span>
                    </label>
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <label>
                        <input type="checkbox" checked="checked">
                        <span>Появление в продаже</span>
                    </label>
                </td>
            </tr>
        </table>-->

        <table class="collapsible">
            <caption><p><span><?= Yii::t('Profile', 'Изменение пароля'); ?></span></p></caption>
            <tr>
                <th><?php echo $form->label($model,'old_password'); ?>:</th>
                <td><?php echo $form->passwordField($model,'old_password'); ?></td>
            </tr>
            <tr>
                <th><?php echo $form->label($model,'password'); ?>:</th>
                <td><?php $model->password = ''; echo $form->passwordField($model,'password'); ?></td>
            </tr>
            <tr>
                <th><?php echo $form->label($model,'repassword'); ?>:</th>
                <td><?php echo $form->passwordField($model,'repassword'); ?></td>
            </tr>
        </table>

        <div class="buttons">
            <button>Сохранить</button>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <?php
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(
            'calendar',
            'date = new Date();
            sDay = $(".day").val();
            sDay++;
            $(".day").html($(createDateOptions(0, date.getDate())));
            $(".day :nth-child("+sDay+")").attr("selected", "selected");

            $(".month").change(function(){
                sDay = $(".day").val();
                sDay++;
                sMonth = $(this).val();
                sYear = $(".year").val();
                date = new Date(sYear, sMonth, 0);
                $(".day").html($(createDateOptions(0, date.getDate())));
                $(".day :nth-child("+sDay+")").attr("selected", "selected");

            })

            $(".year").change(function(){
                sDay = $(".day").val();
                sDay++;
                sYear = $(this).val();
                sMonth = $(".month").val();
                date = new Date(sYear, sMonth, 0);
                $(".day").html($(createDateOptions(0, date.getDate())));
                $(".day :nth-child("+sDay+")").attr("selected", "selected");
            })

            function createDateOptions(from, to){
                html = \'<option value="0">'. Yii::t('Profile', 'день') .'</option>\';
                for(i = ++from; i <= to; i++){
                    html += \'<option>\' + i + \'</option>\';
                }
                return html;
            }',
          CClientScript::POS_END
        );

        $cs->registerScript(
            'showOptions',
            '$(".collapsible").on("click", "caption", function(e) {
                $(e.delegateTarget).toggleClass("expanded")
            })',
          CClientScript::POS_END
        );
    ?>
</div>