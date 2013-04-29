<div class="lenta">

    <?php $this->widget('UserMain', array('model' => $model, 'news' => $news, 'active' => 'yama')); ?>

	<div class="wall" id="central_block">
	<?php $this->renderPartial('_advert', 
				array( 
					'adverts' => $adverts,
					'model' => $model,
					'more' => $more,
					'offset' => $offset,
					'users' => $users,
				)
			);
	?>
	</div>
	
    <div class="aside">
            <script type='text/javascript'><!--//<![CDATA[
			   var m3_u = (location.protocol=='https:'?'https://adv.migom.by/open_ads/www/delivery/ajs.php':'http://adv.migom.by/open_ads/www/delivery/ajs.php');
			   var m3_r = Math.floor(Math.random()*99999999999);
			   if (!document.MAX_used) document.MAX_used = ',';
			   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
			   document.write ("?zoneid=24");
			   document.write ('&amp;cb=' + m3_r);
			   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
			   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
			   document.write ("&amp;loc=" + escape(window.location));
			   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
			   if (document.context) document.write ("&context=" + escape(document.context));
			   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
			   document.write ("'><\/scr"+"ipt>");
			//]]>--></script>
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
				jQuery.ajax({\'url\':\'/yama?offset=\'+offset,\'cache\':false,\'success\':function(html){ 
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