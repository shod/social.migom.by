<div class="lenta">
    <?php $this->widget('UserMain', array('model' => $model, 'active' => Yii::app()->controller->id)); ?>
	
	<div class="wall">
		<div id="central_block" class="messages">
				<?php $this->renderPartial('posts/_posts', array('model' => $model)); ?>
		</div>
    </div>
</div>

<?php
    $cs = Yii::app()->getClientScript();
	
	$cs->registerScript(
        'ajaxNewsOffset',
        'jQuery(function($) {
		
            $(\'body\').on(\'click\',\'#central_block .show-next\',function(){ 
				$(this).find(".wait").show();
				arrId = this.id.split("_"); 
				offset = arrId[1]; 
				var block = this;
				jQuery.ajax({\'url\':\'/user/comments/'.$model->message_id.'?offset=\'+offset,\'cache\':false,\'success\':function(html){ 
						$(this).find(".wait").hide();
						$(block).remove(); 
						$(html).hide().appendTo("#central_block").slideDown(); 
					}});
					return false;
				});
			});
			
			$(document).on(\'click\', \'.post\',function(){
				window.location = \''. Yii::app()->params['socialBaseUrl'].'/messages/send/' . '\'+$(this).find(\'.message\').attr(\'id\');
			})
			

			$(\'body\').on(\'click\',\'#central_block .show-next\',function(){ 
				$(this).find(".wait").show();
				arrId = this.id.split("_"); 
				offset = arrId[1]; 
				var block = this;
				jQuery.ajax({\'url\':\'/messages?offset=\'+offset,\'cache\':false,\'success\':function(html){ 
					$(this).find(".wait").hide();
					$(block).remove(); 
					$(html).hide().appendTo("#central_block").slideDown(); 
				}});
				return false;
			});

			
		',
      CClientScript::POS_END
    );