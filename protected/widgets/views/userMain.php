<div class="navigation">
	<div class="category <?php if($this->active == 'news'): ?>current<?php endif; ?>">
		<div class="heading">
            <?= CHtml::link(Yii::t('Site', 'Лента'), array('/user'), array('class' => 'title')) ?>
			<div class="count">??</div>
		</div>
		<div class="options">
                        <label><?= CHtml::checkBox('comment', !in_array('comment', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <span><?= Yii::t('User', 'Комментарии'); ?></span></label>
                        <label><?= CHtml::checkBox('reviews', !in_array('reviews', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <span><?= Yii::t('User', 'Отзывы'); ?></span></label>
                        <label><?= CHtml::checkBox('prices', !in_array('prices', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <span><?= Yii::t('User', 'Снижение цены'); ?></span></label>
                        <label><?= CHtml::checkBox('inSale', !in_array('inSale', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <span><?= Yii::t('User', 'Появление в продаже'); ?></span></label>
		</div>
	</div>

<!--	<div class="category">
		<div class="heading">
			<a href="lenta-things.html" class="title">Мои вещи</a>
			<div class="count">4</div>
		</div>
		<div class="options">
			<label><input type="checkbox" checked="checked"><span>Комментарии</span></label>
			<label><input type="checkbox" checked="checked"><span>Отзывы</span></label>
			<label><input type="checkbox" checked="checked"><span>Снижение цены</span></label>
			<label><input type="checkbox" checked="checked"><span>Появление в продаже</span></label>
		</div>
	</div>-->
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