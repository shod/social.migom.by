<div class="lenta">

    <?php $this->widget('UserMain', array('model' => $model, 'active' => 'profile')); ?>

    <div class="main profile">
        <div class="summary">
            <div class="avatar"><?= UserService::printAvatar($model->id, $model->fullName, 96, false); ?></div>
			<div class="name">
                <strong><?= $model->fullName; ?></strong>
                <?php if($model->id == Yii::app()->user->id): ?>
                    <?= CHtml::link(Yii::t('Profile', 'Редактировать профиль'), array('/profile/edit')) ?>
				<?php else: ?>
					<?= (Yii::app()->cache->get('online_user_' . $model->id)) ? '<span class="online">'.Yii::t('Profile', 'Сейчас на сайте').'</span>' : EasterEggService::getAwayStatus($model->profile->sex) ?>
					<div class="profile-send-message"><button class="button_yellow search-button" onclick="window.location = '<?= $this->createUrl('/messages/send', array('id' => $model->id)) ?>'">Написать сообщение</button></div>
                <?php endif; ?>
            </div>
            <div class="info"><?= Yii::t('Profile', 'Дата регистрации'); ?><strong><?= SiteService::timeToDate($model->date_add, true) ?></strong></div>
			<?php if($model->google_oauth || $model->vkontakte || $model->facebook): ?>
				<div class="info"><?= Yii::t('Profile', 'Используемые соц.сети:'); ?><strong></strong>
				<?php if($model->google_oauth): ?>
					<span class="google_oauth" title="Google">&nbsp</span>
				<?php endif;?>
				<?php if($model->vkontakte): ?>
					<a href="http://vk.com/id<?= $model->vkontakte->soc_id ?>" style="text-decoration: none;" class="vkontakte" title="Vkontakte">&nbsp</a>
				<?php endif;?>
				<?php if($model->facebook): ?>
					<a href="http://www.facebook.com/profile.php?id=<?= $model->facebook->soc_id ?>" style="text-decoration: none;" class="facebook" title="Facebook">&nbsp</a>
				<?php endif;?>
				</div>
			<?php endif; ?>
            <!--<div class="info"><?= Yii::t('Profile', 'Просмотров профиля'); ?><strong>326</strong></div>-->
        </div>
        <table>
            <caption><span><?= Yii::t('Profile', 'Общая информация'); ?></span></caption>
			<tr>
				<th><?= $model->getAttributeLabel('nickName') ?>:</th>
				<td><?= $model->login; ?></td>
			</tr>
			<?php if($model->profile->name): ?>
            <tr>
                <th><?= $model->profile->getAttributeLabel('name') ?>:</th>
                <td><?= $model->profile->name; ?></td>
            </tr>
			<?php endif; ?>
			<?php if($model->profile->surname): ?>
            <tr>
                <th><?= $model->profile->getAttributeLabel('surname') ?>:</th>
                <td><?= $model->profile->surname; ?></td>
            </tr>
			<?php endif; ?>
			<?php if($model->profile->sex): ?>
            <tr>
                <th><?= $model->profile->getAttributeLabel('sex') ?>:</th>
                <td><?= Yii::t('Profile', Users_Profile::$sexs[$model->profile->sex]); ?></td>
            </tr>
			<?php endif; ?>
			<?php if(SiteService::dateFormat($model->profile->birthday)): ?>
            <tr>
                <th><?= $model->profile->getAttributeLabel('birthday') ?>:</th>
                <td><?= SiteService::dateFormat($model->profile->birthday) ?></td>
            </tr>
			<?php endif; ?>
            <?php if($model->profile->city): ?>
            <tr>
                <th><?= $model->profile->getAttributeLabel('city_id') ?>:</th>
                <td><?= $model->profile->city->name ?></td>
            </tr>
            <?php endif; ?>
        </table>
		<?php if($model->expertIn): ?>
		<table>
            <caption><span><?= Yii::t('Profile', 'Эксперт'); ?></span></caption>
            <tr>
			<?php foreach($model->expertIn as $exp): ?>
                <th></th>
                <td><?= $exp->title ?></td>
            </tr>
			<?php endforeach; ?>
        </table>
		<?php endif; ?>
		<?php if($news): ?>
		<table>
            <caption><span><?= Yii::t('Profile', 'Материалы'); ?></span></caption>
			<?php if($news): ?>
            <tr>
                <th><?= $news ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'news', $news) ?> (<?= CHtml::link(Yii::t('Site', 'просмотреть'), array('/user/authorNews', 'id' => $model->id)); ?>)</td>
            </tr>
			<?php endif; ?>
			<?php if($article): ?>
			<tr>
                <th><?= $article ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'articles', $article) ?> (<?= CHtml::link(Yii::t('Site', 'просмотреть'), array('/user/authorArticle', 'id' => $model->id)); ?>)</td>
            </tr>
			<?php endif; ?>
        </table>
		<?php endif; ?>
		<?php if($model->getCountNewsComments() || $model->getCountArticleComments() || $model->getCountProductComments()): ?>
        <table>
            <caption><span><?= Yii::t('Profile', 'Активность на сайте'); ?></span></caption>
			<?php 
					$cl = Yii::app()->cache->get('comments_likes_count_user_' . $model->id);
					$cdl = Yii::app()->cache->get('comments_dislikes_count_user_' . $model->id);
					$new = new Comments_News();
					$arrCarma = $new->getUserCarma($model->id);
					if($cl === false){
						$cl = $arrCarma['likes'];
						Yii::app()->cache->set('comments_likes_count_user_' . $model->id, $cl, 60 * 10);
					}
					if($cdl === false){
						$cdl = $arrCarma['dislikes'];
						Yii::app()->cache->set('comments_dislikes_count_user_' . $model->id, $arrCarma['dislikes'], 60 * 10);
					}
				?>
			<?php if($model->getCountNewsComments()): ?>
            <tr>
                <th><?= $model->getCountNewsComments() ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'comments to news', $model->getCountNewsComments()) ?> (<?= CHtml::link(Yii::t('Site', 'просмотреть'), array('/user/comments', 'id' => $model->id)); ?>)</td>
            </tr>
			<?php endif; ?>
			<?php if($model->getCountArticleComments()): ?>
			<tr>
                <th><?= $model->getCountArticleComments() ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'comments to articles', $model->getCountArticleComments()) ?> (<?= CHtml::link(Yii::t('Site', 'просмотреть'), array('/user/commentsArticle', 'id' => $model->id)); ?>)</td>
            </tr>
			<?php endif; ?>
			<?php if($model->getCountProductComments()): ?>
			<tr>
                <th><?= $model->getCountProductComments() ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'comments to products', $model->getCountProductComments()) ?> (<?= CHtml::link(Yii::t('Site', 'просмотреть'), array('/user/commentsProduct', 'id' => $model->id)); ?>)</td>
            </tr>
			<?php endif; ?>
			<?php if($cl): ?>
			<tr>
                <th><?= $cl ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'likes', $cl) ?></td>
            </tr>
			<?php endif; ?>
			<?php if($cdl): ?>
			<tr>
                <th><?= $cdl ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'dislikes', $cdl) ?></td>
            </tr>
			<?php endif; ?>
		<!--<tr>
                <th><a href="#">16</a></th>
                <td>отзывов на товар</td>
            </tr>
            <tr>
                <th><a href="#">154</a></th>
                <td>отзывов на продавца</td>
            </tr>-->
        </table>
		<?php endif; ?>
    </div>

</div>