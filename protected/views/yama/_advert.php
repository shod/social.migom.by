<?php
	$limit = UserNews::NEWS_ON_WALL + $offset;
?>
<?php foreach($adverts as $advert): ?>
	<div class="post">
		<div class="related"><?= Yii::t('UserNews', 'Объявление'); ?> <a href="<?= Yii::app()->params['yamaBaseUrl'] . '/ahimsa/' ?><?= $advert->id ?>"><?= mb_substr($advert->description, 0, 50, 'utf8') ; ?></a></div>
		<div class="message">
			<div class="avatar"><?= UserService::printAvatar($model->id, ($model->profile->name) ? $model->profile->name : $model->login); ?></div>
			<?= CHtml::link(($model->profile->name) ? $model->profile->name : $model->login, array('/user/profile', 'id' => $model->id), array('class' => 'author')) ?>
			<span class="date"><?= SiteService::getStrDate($advert->created_at) ?></span>
			<div class="body"><?= CHtml::link($advert->description, Yii::app()->params['yamaBaseUrl'] . '/ahimsa/' . $advert->id); ?></div>
			<?php if($advert->status != 1): ?>
				<b class="tag-1">НЕАКТУАЛЬНО</b>
			<?php endif; ?>
			<?php if($advert->image): ?>
			<div class="image">
				<?= CHtml::image(Yii::app()->params['yamaBaseUrl'] . '/images/ahimsa/' . $advert->id . '/mini/' . $advert->image); ?>
			</div>
			<?php endif; ?>
			<div class="b-market__item-form">
			<?php if($advert->price): ?>   
                <span class="price"><b><?= $advert->price ?></b> <?= Api_Adverts::$currencySymbol[$advert->currency] ?></span>
            <?php endif; ?>
			<?php if(count($advert->auctions)): ?>
				<ul class="offer-list">
				<?php foreach($advert->auctions as $auction): ?>
					<li>
						<?= CHtml::link($users[$auction->user_id]->fullname, Yii::app()->params['socialBaseUrl'] . '/user/' . $auction->user_id) ?>
						 купит за <b><?= $auction->price ?></b> <?= Api_Adverts::$currencySymbol[$advert->currency] ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?php endforeach; ?>
<?php if($more): ?>
	<div id="offset_<?= $limit ?>" class="show-more show-next"><span><?= Yii::t('Site', 'Показать еще'); ?><span class="wait"></span></span></div>
<?php endif; ?>