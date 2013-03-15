<?php
	$limit = UserNews::NEWS_ON_WALL + $offset;
?>
<?php 
		switch($this->action->id){
			case 'comments':
				$urlPostfix = '?news_id=';
				$title = Yii::t('Site', 'Новость');
				break;
			case 'commentsArticle':
				$urlPostfix = '?article_id=';
				$title = Yii::t('Site', 'Обзор');
				break;
			case 'commentsProduct':
				$urlPostfix = '/';
				$title = Yii::t('Site', 'Продукт');
				break;
			default:
				$title = Yii::t('Site', 'Новость');
				$urlPostfix = '?news_id=';
		}
?>
<?php foreach($comments as $comment): ?>
	<div class="post">
		<div class="related"><?= Yii::t('UserNews', 'title:Comments_News'); ?> <a href="<?= Yii::app()->params['migomBaseUrl'] . $urlPostfix ?><?= $comment['entity_id'] ?>"><?= ($comment['title']) ? $comment['title'] : $title ; ?></a></div>
		<div class="message">
			<div class="avatar"><?= UserService::printAvatar($model->id, ($model->profile->name) ? $model->profile->name : $model->login); ?></div>
			<?= CHtml::link(($model->profile->name) ? $model->profile->name : $model->login, array('/user/profile', 'id' => $model->id), array('class' => 'author')) ?>
			<span class="date"><?= SiteService::getStrDate($comment['created_at']) ?> <?= Yii::t('Site', 'назад'); ?></span>
			<?php if(strlen($comment['text']) <= 200): ?>
				<div class="body"><?= $comment['text'] ?></div>
			<?php else: ?>
				<div class="body short"><?= SiteService::subStrEx($comment['text'], 200) ?></div>
				<div class="body full" style="display: none;"><?= $comment['text'] ?></div>
				<a href="" class="expand show-text"><?= Yii::t('Site', 'Показать полностью&hellip;') ?></a>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>
<?php if($more): ?>
	<div id="offset_<?= $limit ?>" class="show-more show-next"><span><?= Yii::t('Site', 'Показать еще'); ?><span class="wait"></span></span></div>
<?php endif; ?>