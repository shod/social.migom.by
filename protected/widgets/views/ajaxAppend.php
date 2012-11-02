        <?php
            $count = count($news->entities);
            $i = 0;
            $limit = UserNews::NEWS_ON_WALL + $this->offset;
        ?>
        <?php foreach($news->entities as $model):
            $i++;
            if($i > $limit){
                break;
            } elseif($i <= $this->offset){
                continue;
            }
        ?>
            <?php
                try {
                    $this->render('news/_'.$model->template, array('model' => $model));
                } catch (Exception $exc) {
//                    echo $exc->getTraceAsString();
                }
            ?>
        <?php endforeach; ?>

        <?php if($count > $limit): ?>
        <div id="offset_<?= $limit ?>" class="show-more show-next"><span><?= Yii::t('Site', 'Показать еще'); ?></span></div>
<!--        <?php //else: ?>
            <div class="show-more"><span>&nbsp;</span></div>-->
        <?php endif; ?>