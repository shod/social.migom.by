<div class="avatar"><?= UserService::printAvatar($user_id, $login, 30); ?></div>
<?= CHtml::link($login, array('/user/profile', 'id' => $user_id), array('class' => 'author')) ?>
<span class="date"><?= SiteService::getStrDate($created_at); ?></span>
<?php if($text): ?>
    <?php if(strlen($text) <= 400): ?>
        <div class="body"><?= $text ?></div>
    <?php else: ?>
        <div class="body short"><?= SiteService::subStrEx($text, 400) ?></div>
        <div class="body full" style="display: none;"><?= $text ?></div>
        <a href="" class="expand show-text"><?= Yii::t('Site', 'Показать полностью&hellip;') ?></a>
    <?php endif; ?>
<?php endif; ?>
<div class="actions">
        <a href="<?= $link ?><?= $entity_id ?>#comment_<?= $comment_id ?>" class="reply"><?= Yii::t('Site', 'Ответить'); ?></a>
        <div class="feedback">
                <?= Yii::t('Site', 'Комментарий полезен?'); ?>
                <span id="<?= $name . '_' . $comment_id . '_like' ?>" class="like ajaxLikeButton"><?= ($likes) ? $likes : 0 ?></span>
                <span id="<?= $name . '_' . $comment_id . '_dislike' ?>" class="dislike ajaxLikeButton"><?= ($dislikes) ? $dislikes : 0 ?></span>
        </div>
</div>