<div class="auth form">
	<div class="logo"></div>
	<p><?= Yii::t('Login', 'Привяжите свой аккаунт из социальных сетей'); ?></p>

	<div class="social">
		<div class="hint"><?= Yii::t('Login', 'Вход и регистрация<br> в 3 клика'); ?></div>
		<?php $this->widget('ext.eauth.EAuthWidget', array('action' => 'site/login')); ?>
	</div>

	<div class="panel signup">
        <?php $this->renderPartial('frm/_registration', array('model' => $regModel)) ?>
	</div>

    <?php $this->renderPartial('frm/_login', array('model' => $model)) ?>
</div>

<div class="popup-fader"></div>
<div class="popup password-recover">
	<div class="close">&times;</div>
	<h1><?= Yii::t('Login', 'Забыли пароль?'); ?></h1>
	<p><?= Yii::t('Login', 'Не расстраивайтесь. Мы вышлем вам новый пароль на указанную электронную почту в ближайшее время.'); ?></p>
	<p><input type="email" value="ivanov@gmail.com"></p>
	<div class="buttons">
	<button>Восстановить пароль</button>
	<span class="cancel-link"><a href="#">Отмена, закрыть окно</a></span>
	</div>
</div>