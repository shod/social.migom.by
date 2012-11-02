<?= Yii::t('Mail', 'Здравствуйте, {name}!

Ваш почтовый адрес {email} был указан при регистрации на Migom.by {date}:

Ваш логин: {login}
Ваш пароль: {pass}

Ссылка на ваш профиль на Migom.by: {link}

Если вы не проходили регистрацию на сайте, просто проигнорируйте это письмо.

С уважением, команда Migom.by {home_link}',
        array(
            '{name}'    => ($user->profile->name) ? $user->profile->name : $user->login,
            '{email}'   => $user->email,
            '{date}'    => SiteService::timeToDate(time()),
            '{login}'   => $user->login,
            '{pass}'    => $password,
            '{link}'    => CHtml::link($user->login, array('/user/profile', 'id' => $user->id)),
            '{home_link}' => 'http://www.migom.by/',
        )
); ?>