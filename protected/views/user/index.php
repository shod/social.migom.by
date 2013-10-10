<div class="lenta">

    <?php $this->widget('UserMain', array('model' => $model, 'news' => $news, 'active' => 'news')); ?>
	
	

    <div class="wall">
            <?php if(Yii::app()->user->id == $model->id): ?>
                <?php $this->widget('UserNews', array('user_id' => Yii::app()->user->id, 'news' => $news)); ?>
            <?php endif; ?>
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