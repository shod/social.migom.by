<div class="post">
    <div class="related"><?= Yii::t('UserNews', 'title:'.$model->name); ?> <a href="<?= $model->link ?><?= $model->entity_id ?>"><?= ($model->title)? $model->title : '#'.$model->entity_id ; ?></a><div id="<?= $model->name . '_' . $model->id ?>_delete" class="close ajaxNewDelete">
            </div></div>
        <div class="message">
                <div class="avatar"><?= UserService::printAvatar($model->auction->user_id, $model->auction->login); ?></div>
                <?= CHtml::link($model->auction->login, array('/user/profile', 'id' => $model->auction->user_id), array('class' => 'author')) ?>
                <span class="date"><?= SiteService::getStrDate($model->created_at) ?></span>
				<?php if($model->auction->price): ?>
					<div class="b-market__item-form">
						<div class="offer">купит за <b><?= $model->auction->price ?></b> <?= Api_Adverts::$currencySymbol[$model->auction->currency] ?></div>
					</div>
				<?php endif; ?>
				<div class="body"><?= $model->text ?></div>
				<?php if(isset($_GET['debug'])): ?>
					<?php dd($model->image); ?>
				<?php endif; ?>
                <?php if($model->image): ?>
                    <div class="attachments">
                        <?= CHtml::link(CHtml::image($model->image),  $model->link . $model->entity_id); ?>
                    </div>
                <?php endif; ?>
        </div>
		<div class="clear"></div>
</div>