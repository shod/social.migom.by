<?php foreach($messages as $message):?>
	<?php $this->renderPartial('dialog/_post', array('message' => $message, 'user' => $user)); ?>
<?php endforeach; ?>