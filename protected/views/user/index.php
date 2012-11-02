<div class="lenta">

    <?php $this->widget('UserMain', array('model' => $model, 'news' => $news, 'active' => 'news')); ?>

    <div class="wall">
            <?php if(Yii::app()->user->id == $model->id): ?>
                <?php $this->widget('UserNews', array('user_id' => Yii::app()->user->id, 'news' => $news)); ?>
            <?php endif; ?>
    </div>

</div>