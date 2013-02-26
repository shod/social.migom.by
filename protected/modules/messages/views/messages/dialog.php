<div class="lenta">
    <?php $this->widget('UserMain', array('model' => $model, 'active' => Yii::app()->controller->id)); ?>
	
	<div class="wall">
		<div id="central_block">
			<div class="messages-breadcrumbs clearfix">
				<?= CHtml::link(Yii::t('Messages', 'Все сообщения'), array('/messages')) ?>
				<span class="arrow">></span> <?= Yii::t('Messages', 'диалог с')?> 
				<?= CHtml::link($user->fullname, array('/user/' . $user->id), array('class' => 'author')) ?>
				<?= CHtml::link(Yii::t('Messages', 'Отмена'), array('/messages'), array('id' => 'dialog-cancel')) ?>
			</div>
			<div class="filters">
				<?php $periods = MessageService::getOrderParams($first)?>
				<?php if(count($periods)): ?>
					<?= Yii::t('Messages', 'Показать за:'); ?>
				<?php endif; ?>
				<?php foreach($periods as $t => $time): ?>
				<?= CHtml::link(
							Yii::t('Messages', $t), 
							array('', 'id' => $user->id, 'date' => $time, 'active' => $t),
							array('class' => ($t == Yii::app()->request->getParam('active', '', 'str')) ? 'active' : '')
						); ?>
				<?php endforeach;?>
			</div>
			<div id="dialog-posts">
				<?php $this->renderPartial('dialog/_posts', array('messages' => $messages, 'user' => $user)); ?>
			</div>
			<?php $this->renderPartial('dialog/_form', array('model'=>$model, 'textModel' => $textModel, 'user' => $user)); ?>
		</div>
    </div>
</div>