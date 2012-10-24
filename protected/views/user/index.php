<div class="darker png_scale" style="display: none;"></div>

<?php $this->widget('UserMain', array('model' => $model, 'news' => $news, 'active' => 'news')); ?>

<div style="float: left; border: 2px solid black; width: 600px;">
    <?php if(Yii::app()->user->id == $model->id): ?>
        <?php $this->widget('UserNews', array('user_id' => Yii::app()->user->id, 'news' => $news)); ?>
    <?php else: ?>
        
    <?php endif; ?>
</div>
<div class="clear"></div>

<div class="footer">
    <div class="copy">&copy; 2006&mdash;2012 Migom.by&nbsp;<strong>Минск</strong>, <strong>Беларусь</strong>&nbsp;&nbsp;</div>
</div>