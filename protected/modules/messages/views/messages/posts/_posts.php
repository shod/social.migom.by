<?php
	$limit = Messages::DIALOGS_LIMIT + Yii::app()->request->getParam('offset', 0, 'int');
?>
<?php $searchDialogsInfo = $model->searchDialogs(Yii::app()->user->id, Yii::app()->request->getParam('offset', 0, 'int')); ?>
<?php  foreach($searchDialogsInfo['messages'] as $dialog): ?>
	<?php if($dialog['user_id'] == $dialog['sender_id']): ?>
		<?php $this->renderPartial('posts/_to', array('dialog' => $dialog, 'users' => $searchDialogsInfo['users'])); ?>
	<?php else: ?>
		<?php $this->renderPartial('posts/_from', array('dialog' => $dialog, 'users' => $searchDialogsInfo['users'])); ?>
	<?php endif; ?>
<?php endforeach; ?>
<?php if($searchDialogsInfo['more']): ?>
	<div id="offset_<?= $limit ?>" class="show-more show-next"><span><?= Yii::t('Site', 'Показать еще'); ?><span class="wait"></span></span></div>
<?php endif; ?>