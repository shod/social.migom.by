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
			<form method="post">
            <caption><span><?= Yii::t('Profile', 'Мои подписки'); ?></span></caption>
			<?php foreach($data as $key => $tags): ?>
				<tr>
					<th><input type="checkbox" value="1" name="subscribe_group[<?= $key ?>]" id="group_<?= $key ?>"></th>
					<td class="checkbox_info"><label for="group_<?= $key ?>"><?php foreach($tags as $tag):?><?= $tag ?> <?php endforeach; ?></label></td>
				</tr>
			<?php endforeach; ?>
				<tr>
					<th></th>
					<td>
						<?php if($count > Subscribes::LIMIT): ?>
							<?php for($i = 0; $i < $count; $i = $i + Subscribes::LIMIT): ?>
								<?= CHtml::link(($i / Subscribes::LIMIT) + 1, array('', 'offset' => $i)); ?>
							<?php endfor;?>
						<?php endif; ?>
					</td>
				</tr>
			<tr>
				<th></th>
				<td class="delete_button"><button class="button_yellow search-button">Удалить</button></td>
			</tr>
			</form>
        </table>
    </div>

</div>