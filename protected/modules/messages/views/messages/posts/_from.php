<div class="post <?php if($dialog['text_status'] == 0): ?>unread<?php endif; ?>">
	<div class="message" id="<?= $dialog['sender_id'] ?>">
		<div class="avatar">
			<?= UserService::printAvatar($dialog['sender_id'], $users[$dialog['sender_id']]->fullName); ?>
		</div>
		<?= CHtml::link($users[$dialog['sender_id']]->fullName, array('/user/'.$dialog['sender_id']), array('class' => 'author')); ?>
		<span class="date"><?= SiteService::getStrDate($dialog['created_at']) ?></span>
		<?php if(strlen($dialog['text']) <= 200): ?>
			<div class="body"><?= $dialog['text'] ?></div>
		<?php else: ?>
			<div class="body"><?= SiteService::subStrEx($dialog['text'], 200) ?></div>
		<?php endif; ?>
	</div>
</div>