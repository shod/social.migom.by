<?php
	$limit = UserNews::NEWS_ON_WALL + $offset;
?>
<?php foreach($news as $new): ?>
	<div class="post">
		<div class="related"><?= Yii::t('UserNews', 'title:Article'); ?> <a href="<?= Yii::app()->params['migomBaseUrl'].'?news_id=' ?><?= $new->id ?>"><?= $new->title ; ?></a>
		<? if($newsCntComments[$new->id] > 0): ?><a rel="nofollow" href="<?= Yii::app()->params['migomBaseUrl'].'?news_id=' ?><?= $new->id ?>#comments" target="_blank">
		<span class="img-comment" style="padding-top:0px"><?=$newsCntComments[$new->id];?><div class="hvost">&nbsp;</div></span></a>
		<? endif; ?>
		</div>
		<div class="message">
			<div class="avatar"><?= UserService::printAvatar($model->id, ($model->profile->name) ? $model->profile->name : $model->login); ?></div>
			<?= CHtml::link(($model->profile->name) ? $model->profile->name : $model->login, array('/user/profile', 'id' => $model->id), array('class' => 'author')) ?>
			<span class="date"><?= SiteService::getStrDate(strtotime($new->start_date)) ?> <?= Yii::t('Site', 'назад'); ?></span>
			<div class="body"><?= $new->anounce_text ?></div>
			<?= CHtml::image(Yii::app()->params['staticBaseUrl'].'/img/news/'.$new->id.'/main-mini.jpg', $new->title); ?>
		</div>
	</div>
<?php endforeach; ?>
<?php if($more): ?>
	<div id="offset_<?= $limit ?>" class="show-more show-next"><span><?= Yii::t('Site', 'Показать еще'); ?><span class="wait"></span></span></div>
<?php endif; ?>