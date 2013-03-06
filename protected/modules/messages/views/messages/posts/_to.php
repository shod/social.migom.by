<div class="post">
	<div class="message" id="<?= $dialog['to'] ?>">
			<div class="avatar" >
				<?= UserService::printAvatar($dialog['to'], $users[$dialog['to']]->fullName); ?>
			</div>
			<?= CHtml::link($users[$dialog['to']]->fullName, array('/user/'.$dialog['to']), array('class' => 'author')); ?>
			<span class="date"><?= SiteService::getStrDate($dialog['created_at']) ?></span>
		<!--div class="body">
			<?= CHtml::link(Yii::t('Messages', 'Перейти к диалогу'), array('messages/send', 'id' => $dialog['to']), array('class' => 'goto-dialog')); ?>
		</div-->
	</div>

	<div class="post-extras <?php if($dialog['text_status'] == 0): ?>unread<?php endif; ?>">
		<div class="message message-extras" >
				<div class="avatar">
					<?= UserService::printAvatar(Yii::app()->user->id, Yii::app()->user->name); ?>
				</div>
				<?= CHtml::link(Yii::app()->user->name, array('/user/profile'), array('class' => 'author')); ?>
				<div class="body"><?= SiteService::subStrEx($dialog['text'], 200) ?></div>
		</div>
	</div>
</div>