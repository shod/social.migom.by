<div class="lenta">
    <?php $this->widget('UserMain', array('model' => $model)); ?>

    <div class="wall">
		<div id="central_block">
			<?php $this->renderPartial('comments/_post', array('comments' => $comments, 'model' => $model, 'offset' => $offset, 'more' => $more)); ?>
		</div>
    </div>
</div>
<?php
    $cs = Yii::app()->getClientScript();

	$cs->registerScript(
        'showText',
        'jQuery(function($){$(\'body\').on(\'click\',\'.message .show-text, .comment .show-text\',function(){ $(this).hide(); $(this).parent().find(".body.short").hide(); $(this).parent().find(".body.full").slideDown(); return false;}); });',
      CClientScript::POS_END
    );
	
	$cs->registerScript(
        'ajaxNewsOffset',
        'jQuery(function($) {
            $(\'body\').on(\'click\',\'#central_block .show-next\',function(){ 
				$(this).find(".wait").show();
				arrId = this.id.split("_"); 
				offset = arrId[1]; 
				var block = this;
				jQuery.ajax({\'url\':\'/user/comments/'.$model->id.'?offset=\'+offset,\'cache\':false,\'success\':function(html){ 
					$(this).find(".wait").hide();
					$(block).remove(); 
					$(html).hide().appendTo("#central_block").slideDown(); 
				}});
				return false;
			});
        });',
      CClientScript::POS_END
    );
	
?>