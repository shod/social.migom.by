<div class="main" style="float: left;">
    <div class="header">
        <table><tr>
                <td>
                    <?php //if ($model->profile->avatar): ?>
                        <?= UserService::printAvatar($model->id, $model->login) ?>
                    <?php //else: ?>
                        <?php //echo CHtml::image('/images/users/default_avatar.png', 'default avatar', array('style' => 'width:50px; height:50px; border: 1px solid black', 'class' => 'avatar', 'border' => 0)); ?>
                    <?php //endif; ?>
                </td>
                <td style="border-bottom: 2px solid black">
                    <h1 style="margin: 3px;"><?php echo $model->login . ' (' . $model->profile->full_name . ')'; ?></h1>
                    <?php if (Yii::app()->user->id == $model->id): ?>
                        <?= CHtml::link(Yii::t('Site', 'Edit User Info'), array('/user/edit')); ?>
                        <?php if(!$model->email): ?>
                            <b style="color: red;"><?= Yii::t('Site', 'Your email are empty!'); ?></b>
                        <?php endif;  ?>
                    <?php endif; ?>
                </td>
            </tr></table>
    </div>

    <div class="outer">

        <div class="container">
            <div class="content">
                <div class="intend"></div>
            </div><!--/content-->
        </div><!--/container-->

        <?php if($model->id == Yii::app()->user->id): ?>
            <div class="sidebar">
                <ul>
                    <li>
                        <?= CHtml::link(Yii::t('Site', '<b>News</b>'), array('user/index')); ?>
                        <?php if($this->active == 'news'): ?>
                        <ul>
                            <li><?= CHtml::checkBox('comment', !in_array('comment', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <?= Yii::t('User', 'Comments'); ?></li>
                            <li><?= CHtml::checkBox('like', !in_array('like', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <?= Yii::t('User', 'Likes'); ?></li>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <li><?= CHtml::link(Yii::t('Site', 'Exit'), array('site/logout')); ?></li>
                </ul>        
            </div><!--/sidebar-->
        <?php endif; ?>

    </div><!--/outer-->
</div>

<?php 
    $cs = Yii::app()->getClientScript();  
    $cs->registerScript(
        'ajaxNewsCheckboxes',
        'jQuery(function($) {
            $(\'body\').on(\'click\',\'.newsFilter\',function(){jQuery.ajax({\'url\':\'/ajax/userNews?filter=\'+this.name,\'cache\':false,\'success\':function(html){jQuery("#central_block").html(html)}});return true;});
        });',
      CClientScript::POS_END
    );
?>