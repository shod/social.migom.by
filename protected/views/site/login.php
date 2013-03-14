<div class="auth form">
	<div class="logo"></div>
	<p><?= Yii::t('Login', 'Войти через:'); ?></p>

	<div class="social">
		<div class="hint"><?= Yii::t('Login', 'Вход и регистрация<br> в 3 клика'); ?></div>
		<?php $this->widget('core.extensions.eauth.EAuthWidget', array('action' => 'site/login')); ?>
	</div>

	<div class="panel signup">
        <?php $this->renderPartial('frm/_registration', array('model' => $regModel)) ?>
	</div>

    <?php $this->renderPartial('frm/_login', array('model' => $model)) ?>
</div>

<div class="popup-fader"></div>

<?php $this->renderPartial('frm/_remind', array('model' => $remindModel)) ?>