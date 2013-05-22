<div id="post-form" class="post">
	<div class="message">
		<div class="avatar">
			<?= UserService::printAvatar(Yii::app()->user->id, Yii::app()->user->name); ?>
		</div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'messages-form',
			'enableAjaxValidation'=>false,
			'focus' => 'yt0',
		)); ?>

			<ul>
				<?php $textModel->text = $message_prefix; ?>
				<li><?php echo $form->textArea($textModel,'text'); ?></li>
				<li>
					<?= CHtml::ajaxSubmitButton(
							Yii::t('Messages', 'Отправить'), 
							array('/messages/send', 'id' => $user->id, 'ahimsa' => Yii::app()->request->getParam('ahimsa', '0', 'int')), 
							array(
								'beforeSend' => 'function(){ if($("#Messages_Text_text").val() == "") {return false;}; }',
								'success' => 'function(data){ $("#dialog-posts").append(data); }', 
								'complete' => 'function(){ $("#Messages_Text_text").val("") }'
							), 
							array('class' => 'button_yellow search-button', 'id' => 'messageSubmit')
						) ?>
				</li>
			</ul>

		<?php $this->endWidget(); ?>
	</div>
</div>

<?php
    $cs = Yii::app()->getClientScript();
	
	$cs->registerScript(
        'formSend',
        'jQuery(function($) {
			$("#Messages_Text_text").keypress(function(event){
				if ((event.keyCode == 13 || event.keyCode == 10) && event.ctrlKey) {
					$("#messageSubmit").click();
				}
			});
			
			$(document).on(\'mousemove\', \'#central_block\', function(event){
				if($(\'#central_block .unreadMe\').hasClass(\'unreadMe\')){
					$(\'#central_block .unreadMe\').animate({ backgroundColor: "white" }, {duration: 5000})
					$(\'#central_block .unreadMe\').removeClass(\'unreadMe\')
					jQuery.post("'.$this->createUrl("/messages/readdialog", array("id" => $user->id)).'", {});
				}
			});
		});
		',
      CClientScript::POS_END
    );