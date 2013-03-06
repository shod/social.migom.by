<div class="lenta">
    <?php $this->widget('UserMain', array('model' => $model)); ?>

    <div class="wall">
		<div id="central_block">
			<?php $this->renderPartial('authorArticle/_articles', array('news' => $news, 'model' => $model, 'offset' => $offset, 'more' => $more, 'newsCntComments' => $newsCntComments)); ?>
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
				jQuery.ajax({\'url\':\'/user/authorArticle/'.$model->id.'?offset=\'+offset,\'cache\':false,\'success\':function(html){ 
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