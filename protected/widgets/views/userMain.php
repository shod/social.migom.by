<div class="navigation">
	<div class="category wall_cat <?php if($this->active == 'news'): ?>current<?php endif; ?>">
		<div class="heading">
            <?= CHtml::link(Yii::t('Site', 'Лента'), array('/user'), array('class' => 'title')) ?>
			<div class="count" style="<?php if(!Yii::app()->notify->getNotifyCount('wall')): ?>display:none;<?php endif; ?>"><?= Yii::app()->notify->getNotifyCount('wall') ?></div>
			
				
			
		</div>
        <?php if($model instanceof Users && $model->id == Yii::app()->user->id): ?>
		<div class="options">
                        <label><?= CHtml::checkBox('comment', !in_array('comment', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <span><?= Yii::t('User', 'Комментарии'); ?></span></label>
                        <label><?= CHtml::checkBox('price_down', !in_array('price_down', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <span><?= Yii::t('User', 'Снижение цены'); ?></span></label>
						<label><?= CHtml::checkBox('in_sale', !in_array('in_sale', ($news)?$news->disable_entities:array()), array('class' => 'newsFilter')) ?> <span><?= Yii::t('User', 'Появление в продаже'); ?></span></label>
		</div>
        <?php endif; ?>
	</div>
	<div class="category messages_cat <?php if($this->active == 'messages'): ?>current<?php endif; ?>">
		<div class="heading">
			<?= CHtml::link(Yii::t('Site', 'Мои сообщения'), array('/messages'), array('class' => 'title')) ?>
				<div class="count" style="<?php if(!Yii::app()->notify->getNotifyCount('messages')): ?>display:none;<?php endif; ?>"><?= Yii::app()->notify->getNotifyCount('messages') ?></div>
		</div>
	</div>
	<!--<div class="category things_cat <?php if($this->active == 'things'): ?>current<?php endif; ?>">
		<div class="heading">
			<?= CHtml::link(Yii::t('Site', 'Мои вещи'), array('/things'), array('class' => 'title')) ?>
		</div>
		<div class="options">
			<label><?= CHtml::checkBox('wish', !in_array('wish', ($news)?$news->disable_entities:array()), array('class' => 'thingsFilter')) ?> <span><?= Yii::t('User', 'Я хочу'); ?></span></label>
			<label><?= CHtml::checkBox('have', !in_array('have', ($news)?$news->disable_entities:array()), array('class' => 'thingsFilter')) ?> <span><?= Yii::t('User', 'У меня есть'); ?></span></label>
		</div>
	</div>-->
	<div class="category yama_cat <?php if($this->active == 'yama'): ?>current<?php endif; ?>">
		<div class="heading">
			<?= CHtml::link(Yii::t('Site', 'Мои объявления'), array('/yama'), array('class' => 'title')) ?>
		</div>
		<div class="options">
		</div>
	</div>
</div>

<?php
    $cs = Yii::app()->getClientScript();
    $cs->registerScript(
        'ajaxNewsCheckboxes',
        '
		jQuery(function($) {
            $(\'body\').on(\'click\',\'.newsFilter\',function(){jQuery.ajax({\'url\':\'/ajax/userNews?filter=\'+this.name,\'cache\':false,\'success\':function(html){jQuery("#central_block").html(html)}});return true;});
        });
		
		jQuery(function($) {
            $(\'body\').on(\'click\',\'.thingsFilter\',function(){jQuery.ajax({\'url\':\'/things?filter=\'+this.name,\'cache\':false,\'success\':function(html){jQuery("#central_block").html(html)}});return true;});
        });
		',
      CClientScript::POS_END
    );
?>