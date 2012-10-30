<div id="central_block">
    <?php if(!$news || !count($news->entities)): ?>
        <div class="show-more" ><span><?= Yii::t('Site', 'На вашей стене пока нет новостей'); ?></span></div>
    <?php else: ?>
        <?php
            $count = count($news->entities);
            $i = 0;
            $limit = UserNews::NEWS_ON_WALL + $this->offset;
        ?>
        <?php foreach($news->entities as $model):
            $i++;
            if($i > $limit){
                break;
            } elseif($i < $this->offset){
                continue;
            }
        ?>
            <?php
//                try {
                    $this->render('news/_'.$model->template, array('model' => $model));
//                } catch (Exception $exc) {
////                    echo $exc->getTraceAsString();
//                }
            ?>
        <?php endforeach; ?>

        <?php if($count > $limit): ?>
        <div id="offset_<?= $limit ?>" class="show-more show-next"><span><?= Yii::t('Site', 'Показать еще'); ?></span></div>
<!--        <?php //else: ?>
            <div class="show-more"><span>&nbsp;</span></div>-->
        <?php endif; ?>

    <?php endif; ?>



</div>

<?php
    $cs = Yii::app()->getClientScript();
    $cs->registerScript(
        'ajaxFilterNews',
        'jQuery(function($){$(\'body\').on(\'click\',\'.ajaxNewDelete\',function(){ var link = this;  $.post("'.
                CController::createUrl('/ajax/deletenew', array('entity' => ''))
            .'"+this.id).success(function() {$(link).parent().parent().remove(); });   return false;}); });',
      CClientScript::POS_END
    );

    $cs->registerScript(
        'ajaxShowMore',
        'jQuery(function($){$(\'body\').on(\'click\',\'.comments .ajaxShowMore\',function(){ var block = this;  $.post("'.
                CController::createUrl('/ajax/showcomments', array('entity' => ''))
            .'"+this.id).success(function(data) { commentBlock = $(block).parent(); commentBlock.find("div").remove(); commentBlock.append(data).hide().slideDown("slow"); });   return false;}); });',
      CClientScript::POS_END
    );

    $cs->registerScript(
        'showOne',
        'jQuery(function($){$(\'body\').on(\'click\',\'.comments .showOne\',function(){ $(this).hide(); $(this).parent().find(".comment").not(":last").slideUp("slow"); $(this).parent().find(".showAll").show(); }); });',
      CClientScript::POS_END
    );

    $cs->registerScript(
        'showAll',
        'jQuery(function($){$(\'body\').on(\'click\',\'.comments .showAll\',function(){ $(this).hide(); $(this).parent().find(".comment").slideDown("slow"); $(this).parent().find(".showOne").show(); }); });',
      CClientScript::POS_END
    );

    $cs->registerScript(
        'ajaxLikeButton',
        'jQuery(function($){$(\'body\').on(\'click\',\'.feedback .ajaxLikeButton\',function(){ var block = this;  $.post("'.
                CController::createUrl('/ajax/walllike', array('entity' => ''))
            .'"+this.id).success(function(data) { data = jQuery.parseJSON(data); if(data.success){$(block).hide(); $(block).html(parseInt($(block).html())+1); $(block).slideDown("slow");} });   return false;}); });',
      CClientScript::POS_END
    );

    $cs->registerScript(
        'showText',
        'jQuery(function($){$(\'body\').on(\'click\',\'.message .show-text, .comment .show-text\',function(){ $(this).hide(); $(this).parent().find(".body.short").hide(); $(this).parent().find(".body.full").slideDown(); return false;}); });',
      CClientScript::POS_END
    );

    $cs = Yii::app()->getClientScript();
    $cs->registerScript(
        'ajaxNewsOffset',
        'jQuery(function($) {
            $(\'body\').on(\'click\',\'#central_block .show-next\',function(){ var block = this; jQuery.ajax({\'url\':\'/ajax/userNews?offset=\'+this.id,\'cache\':false,\'success\':function(html){ $(block).remove(); $(html).hide().appendTo("#central_block").slideDown(); }});return true;});
        });',
      CClientScript::POS_END
    );
?>