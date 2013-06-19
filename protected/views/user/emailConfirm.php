<div class="" style="margin: 20px;font-size: 14px;">
<?= Yii::t('User', ':user, ваша эл. почта подтверждена. Через 5 секунд вы будите перенаправлены в личный профиль. Если этого не произошло, пройдите по :link.', array(
	':user' => $userName,
	':link' => CHtml::link(Yii::t('User', 'ссылке'), array('/profile/edit')),
)); ?>
</div>
<script language = 'javascript'>
  var delay = 5000;
  setTimeout("document.location.href='<?= Yii::app()->getBaseUrl(true) . '/profile/edit' ?>'", delay);
</script>