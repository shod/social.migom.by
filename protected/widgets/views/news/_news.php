<div class="post">
    <div class="related"><?= Yii::t('UserNews', 'title:'.$model->name); ?> <a href="<?= $model->link ?><?= $model->id ?>"><?= ($model->title)? $model->title : Yii::t('Site', 'Новость') ; ?></a><div id="<?= $model->name . '_' . $model->id ?>_delete" class="close ajaxNewDelete">
            </div></div>
        <div class="message">
                <div class="avatar"><?= UserService::printAvatar(Yii::app()->user->id, Yii::app()->user->name); ?></div>
                <a href="javascript:" class="author"><?= Yii::app()->user->name ?></a>
                <span class="date"><?= SiteService::timeRange($model->created_at, time()) ?> <?= Yii::t('Site', 'назад'); ?></span>
                <?php if(strlen($model->text) <= 400): ?>
                    <div class="body">
                            <?= $model->text ?>
                    </div>
                <?php else: ?>
                    <div class="body short">
                        <?= SiteService::subStrEx($model->text, 400) ?>
                    </div>
                    <div class="body full" style="display: none;">
                        <?= $model->text ?>
                    </div>
                    <a href="" class="expand show-text"><?= Yii::t('Site', 'Показать полностью&hellip;') ?></a>
                <?php endif; ?>
                <?php if($model->image): ?>
                    <div class="attachments">
                        <?= CHtml::link($model->imageName, CHtml::image($model->image)); ?>
                    </div>
                <?php endif; ?>
        </div>

        <div class="extras">
            <?php if(count($model->likes->users)): ?>
                <div class="likes">
                    <?php foreach($model->likes->users as $likeUser): ?>
                        <?= UserService::printAvatar($likeUser['id'], $likeUser['login'], 30); ?>
                    <?php endforeach; ?>
                        <span>:)</span>
                </div>
            <?php endif; ?>
            <?php if(count($model->dislikes->users)): ?>
                <div class="dislikes">
                    <?php foreach($model->dislikes->users as $likeUser): ?>
                        <?= UserService::printAvatar($likeUser['id'], $likeUser['login'], 30); ?>
                    <?php endforeach; ?>
                        <span>:(</span>
                </div>
            <?php endif; ?>
        </div>


        <?php if($model->comment->count): ?>
        <div class="comments">
            <div id="<?= $model->name . '_' . $model->id . '_' . $model->template ?>" class="show-more ajaxShowMore"><span><?= Yii::t('Site', 'Показать все {count} ', array('{count}' => $model->comment->count)); ?><?= SiteService::getCorectWordsT('Site', 'comments', $model->comment->count); ?></span></div>

                <div class="comment">
                    <?php $this->render('application.widgets.views.news.comment._comment', array(
                            'user_id' => $model->comment->user_id,
                            'login' => $model->comment->login,
                            'created_at' => $model->comment->created_at,
                            'text' => $model->comment->text,
                            'link' => $model->link,
                            'entity_id' => $model->entity_id,
                            'id' => $model->id,
                            'name' => $model->name,
                            'comment_id' => $model->comment->id,
                            'likes' => $model->comment->likes->count,
                            'dislikes' => $model->comment->dislikes->count,
                        )); ?>
                </div>
        </div>
        <?php endif; ?>
</div>