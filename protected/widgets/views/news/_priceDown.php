<div class="post">
    <div class="related"><?= Yii::t('UserNews', 'title:'.$model->name); ?> <?= CHtml::link($model->title, $model->link . '/' . $model->entity_id) ?>
		<div id="<?= $model->name . '_' . $model->id ?>_delete" class="close ajaxNewDelete"></div>
	</div>
	<div class="message">
			<div class="avatar"><?= CHtml::image(Yii::app()->baseUrl. '/images/users/migom.jpg') ?></div>
			<a href="http://migom.by/" class="author"><?= Yii::t('News', 'Migom.by'); ?></a>
			<span class="date"><?= SiteService::getStrDate($model->created_at) ?></span>
			<div class="body"><?= Yii::t('News', 'Теперь стоит'); ?> <strong>$<?= $model->cost ?></strong></div>
			<div class="attachments">
				<?= CHtml::link(CHtml::image($model->image), $model->link . '/' . $model->entity_id) ?>
				<!--<a href="javascript:">Сообщите, когда станет еще дешевле</a>-->
			</div>
	</div>
</div>