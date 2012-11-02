<?= Yii::t('Mail', 'Здравствуйте, {name}!

Вы получили это письмо, потому что {date} был сделан запрос на восстановление вашего пароля на сайте Migom.by.

Ваш логин: {login}
Ваш пароль: {pass}

Ссылка на ваш профиль на Migom.by: {link}

Если вы не делали такой запрос, просто проигнорируйте это письмо.

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