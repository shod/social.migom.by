<div id="<?= $model->name ?>_<?= $model->id ?>">
    <?php echo CHtml::link(
                Yii::t('Site', 'Delete'), 
                array('user/deletenew', 'entity' => $model->name, 'id' => $model->id), 
                array('style'=>'float: right;', 'class' => 'ajaxNewDelete')
            ); ?>
    <div style="float: left;">
        <div style="float: left;">
            <?= UserService::printAvatar(Yii::app()->user->id, Yii::app()->user->name); ?>
        </div>
        <div style="float: left;">
                <?= $model->text ?>
            <div class="clear">
                <?= $model->likes->count ?> :)
                <?= $model->dislikes->count ?> :(
            </div>
        </div>
            <div class="clear"></div>
            <?php if($model->comment->text): ?>
                <div style="margin-left: 70px; width: 100%">
                    <div style="background: gray"><?= Yii::t('User', 'Comments'); ?></div>
                    <div style="float: left;">
                        <?= UserService::printAvatar($model->comment->user_id, $model->comment->login); ?>
                    </div>
                    <div style="float: left;">
                        <?php if($model->comment->text): ?>
                            <?= $model->comment->text ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        
    </div>
    
    <div class="clear"></div>
</div>