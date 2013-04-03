<div class="lenta">

    <?php $this->widget('UserMain', array('model' => $model, 'news' => $news, 'active' => 'things')); ?>

	<div class="wall" id="central_block">
	<?php $this->renderPartial('things', 
				array( 
					'res' => $res,
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
        'ajaxThings',
        '
		jQuery(function($) {
            $(\'body\').on(\'click\',\'.thing .close\',function(){ var thing = $(this).parent(); thing.remove(); jQuery.ajax({\'url\':\'/things/delete/\'+thing.attr(\'id\')}); return true;});
			$(\'body\').on(\'click\',\'.thing .check\',function(){ if($(this).parent().hasClass("checked")){return false;}; var thing = $(this).parent().parent(); 
				thing.find(".status").css("margin-left","36px"); 
				thing.find(".check").css("display","block").css("background-position","center bottom").css("position","relative").animate(
					{left: \'-=35px\'}, 
					2000, 
					function(){ 
						thing.find(".status").css("margin-left","");
						thing.find(".check").css("left",""); 
						thing.find(".check").css("position",""); 
						thing.find(".status").addClass(\'checked\')
					}
				);
			jQuery.ajax({\'url\':\'/things/setHave/\'+thing.attr(\'id\')}); return true;});
        });

		',
      CClientScript::POS_END
    );
?>