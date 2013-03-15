	<?php 
		if(!isset($class)){
			if($message->textTable->status == Messages_Text::UNREAD){
				if($message->sender_id == Yii::app()->user->id){
					$class = 'unread';
				} else {
					$class = 'unreadMe';
				}
			} else {
				$class = '';
			}
		}
	?>
	<div class="post <?= $class; ?>">
	<?php $senderFullName = ($message->sender_id == Yii::app()->user->id) ? Yii::app()->user->name : $user->fullName ?>
		<div class="message">
				<div class="avatar">
					<?= UserService::printAvatar($message->sender_id, $senderFullName); ?>
				</div>
				<?= CHtml::link($message->sender->fullName, array('/user/'.$message->sender_id), array('class' => 'author')); ?>
			<span class="date"><?= SiteService::getStrDate($message->textTable->created_at) ?></span>
			<div class="body"><?= $message->textTable->text ?></div>
		</div>
	</div>