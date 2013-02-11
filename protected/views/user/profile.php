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
					<?= (Yii::app()->cache->get('online_user_' . $model->id)) ? '<span class="online">'.Yii::t('Profile', 'Сейчас на сайте').'</span>' : (($model->profile->sex == 2) ? Yii::t('Profile', 'Отошла'):Yii::t('Profile', 'Отошёл')) ?>
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
			<?php if($model->id != Yii::app()->user->id): ?>
				<?= CHtml::link('Написать сообщение', array('/messages/send', 'id' => $model->id)); ?>
			<?php endif; ?>
            <!--<div class="info"><?= Yii::t('Profile', 'Просмотров профиля'); ?><strong>326</strong></div>-->
        </div>
        <table>
            <caption><?= Yii::t('Profile', 'Общая информация'); ?></caption>
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
		<?php if($news): ?>
		<table>
            <caption><?= Yii::t('Profile', 'Материалы'); ?></caption>
            <tr>
                <th><?= $news ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'news', $news) ?> (<?= CHtml::link(Yii::t('Site', 'просмотреть'), array('/user/authorNews', 'id' => $model->id)); ?>)</td>
            </tr>
        </table>
		<?php endif; ?>
		<?php if($model->getCountComments()): ?>
        <table>
            <caption><?= Yii::t('Profile', 'Активность на сайте'); ?></caption>
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
            <tr>
                <th><?= $model->getCountComments() ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'comments', $model->getCountComments()) ?> (<?= CHtml::link(Yii::t('Site', 'просмотреть'), array('/user/comments', 'id' => $model->id)); ?>)</td>
            </tr>
			<tr>
                <th><?= $cl ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'likes', $cl) ?></td>
            </tr>
			<tr>
                <th><?= $cdl ?></th>
                <td><?= SiteService::getCorectWordsT('Site', 'dislikes', $cdl) ?></td>
            </tr>
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