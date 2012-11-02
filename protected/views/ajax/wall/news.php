<div id="" class="show-more showOne"><span><?= Yii::t('Site', 'Скрыть'); ?></span></div>
<div id="" class="show-more showAll" style="display: none;"><span><?= Yii::t('Site', 'Показать все {count}', array('{count}' => count($comments))); ?> <?= SiteService::getCorectWordsT('Site', 'comments', count($comments)) ?></span></div>

<?php foreach($comments as $comment): ?>
    <div class="comment">
        <?php $this->render('application.widgets.views.news.comment._comment', array(
                'user_id' => $comment->user_id,
                'login' => $comment->user->login,
                'created_at' => $comment->created_at,
                'text' => $comment->text,
                'link' => News::getLink(array_pop(explode('_', get_class($comment)))),
                'entity_id' => $comment->entity_id,
                'id' => $comment->id,
                'name' => get_class($comment),
                'comment_id' => $comment->id,
                'likes' => $comment->likes,
                'dislikes' => $comment->dislikes,
            )); ?>
    </div>
<?php endforeach;?>