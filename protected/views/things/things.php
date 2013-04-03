	<?php if(empty($res)): ?>
		<div class="show-nomore"><span><?= Yii::t('Things', 'Пусто'); ?></span></div>
	<?php else: ?>
		<?php foreach($res as $p): ?>
			<div class="thing" id="<?= $p['id'] ?>">
				<div class="photo">
					<?= CHtml::link(
							CHtml::image(ProdImgService::getUrl($p['product_id'], $p['section_id'], 'small', $p['name']), $p['name']), 
							Yii::app()->params['migomBaseUrl'] . '/' . $p['product_id']
						); ?>
				</div>
				<div class="status <?php if($p['have']):?>checked<?php endif;?>">
					<div class="heart"></div>
					<div class="check"></div>
				</div>
				<div class="title">
					<?= CHtml::link($p['name'], Yii::app()->params['migomBaseUrl'] . '/' . $p['product_id']); ?>
				</div>
				<?php if($p['haveCont']): ?>
					<div class="owners"><a href="javascript:"><?= $p['haveCont'] ?> <?= SiteService::getCorectWordsT('Things', 'owners', $p['haveCont']) ?></a></div>
				<?php endif; ?>
				<div class="close"></div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
