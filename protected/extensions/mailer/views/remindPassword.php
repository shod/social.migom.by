<div style="background-color: #E5ECF9; border: 2px solid black">
    <?= Yii::t('Mail', 'You are registered on Social.Migom.By. Dear mr.{name}', array('{name}' => $user->login)); ?>
    <?php if(isset($param['password'])): ?>
        <?= Yii::t('Mail', 'Youre password: <b>{pass}</b>', array('{pass}' => $param['password'])); ?>
    <?php endif; ?>
    
</div>