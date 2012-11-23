<?php

class SiteTest extends WebTestCase
{

    public function testRegistration()
	{
            $pass = 123456;

            $this->open('/login');
            $this->assertElementPresent('name=Form_Registration[email]', 'field to enter an email is present');

            $rn = rand(0, 999999);
            $login = "tester{$rn}";
            $email = $login."@example.com";
            $this->type('name=Form_Registration[email]', $email);
            sleep(1);
            $this->assertTextPresent('Email введен не верно');

            $rn = rand(0, 999999);
            $email = "tester{$rn}.com";
            $this->type('name=Form_Registration[email]', $email);
            sleep(1);
            $this->assertTextPresent('Email введен не верно');

            $rn = rand(0, 999999);
            $email = "tester{$rn}@gmail.com";
            $this->type('name=Form_Registration[email]', $email);
            sleep(1);

            $this->clickAndWait("//button");
            $this->assertTextPresent('На вашей стене пока нет новостей');

            $this->clickAndWait("//a[@href='http://social.migom.tst/profile/edit']");
            $this->assertElementPresent('id=Users_password');
            $this->assertElementPresent('id=Users_repassword');
            $this->click("//table/caption/p/span");

            $this->type('id=Users_password', $pass);
            $this->type('id=Users_repassword', $pass . "_fail");
            $this->click("id=save");
            sleep(1);
            $this->assertTextPresent('Введите пароль правильно');
//
            $this->type('id=Users_password', $pass);
            $this->type('id=Users_repassword', $pass );
            $this->clickAndWait("id=save");

            $this->assertTitle('Мои новости | Migom.by');

            $this->clickAndWait("//a[@href='http://social.migom.tst/logout/']");
            $this->assertTextPresent('Регистрация в 1 клик');

            $this->type('id=Form_Login_email', $login);
            $this->click("id=login");
            sleep(1);
            $this->assertTextPresent('Заполните');

            $this->type('id=Form_Login_email', $login);
            $this->type('id=Form_Login_password', $login);
            $this->click("id=login");
            sleep(1);
            $this->assertTextPresent('Email введен не верно');

            $this->type('id=Form_Login_email', $email);
            $this->type('id=Form_Login_password', $email);
            sleep(1);
            $this->assertTextPresent('Неверный email или пароль.');

            $this->type('id=Form_Login_password', $pass);
            sleep(1);
            $this->assertTextNotPresent('Email введен не верно');
            $this->assertTextNotPresent('Неверный email или пароль.');

            $this->clickAndWait("id=login");

            $this->assertTitle(Yii::t('Social', 'Профиль {login} | Migom.by', array('{login}' => $login)));

            $this->clickAndWait("//a[@href='http://social.migom.tst/profile/edit']");
            $login = 'testSeleniumUser1';
            $name = 'testSeleniumUserName';
            $surname = 'testSeleniumUserSurName';
            $this->type('id=Users_login', $login);
            $this->type('id=Users_Profile_name', $name);
            $this->type('id=Users_Profile_surname', $surname);

            sleep(1);
            $this->clickAndWait("id=save");

            $this->assertTitle(Yii::t('Social', 'Профиль {login} | Migom.by', array('{login}' => $login)));

            $this->clickAndWait("//a[@href='http://social.migom.tst/profile']");

            $this->assertTextPresent($login);
            $this->assertTextPresent($name);
            $this->assertTextPresent($surname);

            $this->assertTrue(Users::model()->find('email = :em', array(':em' => $email))->delete(), 'удаляем тестового пользователя из базы');
	}

    public function testWall()
    {
        $pass = 123456;

        $this->open('/login');
        $this->assertElementPresent('name=Form_Registration[email]', 'field to enter an email is present');

        $rn = rand(0, 999999);
        $login = "tester{$rn}";
        $email = $login."@mail.com";
        $this->type('name=Form_Registration[email]', $email);
        $this->clickAndWait("//button");
        $this->assertTextPresent('На вашей стене пока нет новостей');

        $testUser = Users::model()->find('email = :em', array(':em' => $email));

        // создаем 2 комментария пользователю
        $comment               = new Comments_News();
        $comment->entity_id    = 1;
        $comment->user_id      = $testUser->id;
        $comment->text         = 'Добрый день, мне очень понравилась ваша статья';
        $comment->status       = array_search('Published', Comments::$statuses);
        $comment->created_at   = time();
        $comment->updated_at   = time();
        $this->assertTrue($comment->save());
        $comment2               = new Comments_News();
        $comment2->entity_id    = 1;
        $comment2->parent_id    = $comment->id;
        $comment2->user_id      = $testUser->id;
        $comment2->text         = 'Спасибо, я буду продолжать писать статьи дальше';
        $comment2->status       = array_search('Published', Comments::$statuses);
        $comment2->created_at   = time();
        $comment2->updated_at   = time();
        $this->assertTrue($comment2->save());

        $this->assertTrue(News::pushComment($comment2, 1), 'Добавляем на стену коммент');

        // меняем пароль
        $this->clickAndWait("//a[@href='http://social.migom.tst/profile/edit']");
        $this->assertElementPresent('id=Users_password');
        $this->assertElementPresent('id=Users_repassword');
        $this->click("//table/caption/p/span");

        $this->type('id=Users_password', $pass);
        $this->type('id=Users_repassword', $pass );
        $this->clickAndWait("id=save");

        $this->assertTitle('Мои новости | Migom.by');

        $this->assertTextPresent('Добрый день, мне очень понравилась ваша статья');
        $this->click('id=comment');
        sleep(1);
        $this->assertTextNotPresent('Добрый день, мне очень понравилась ваша статья');
        $this->open('/');
        $this->assertTextNotPresent('Добрый день, мне очень понравилась ваша статья');
        $this->click('id=comment');
        sleep(1);
        $this->assertTextPresent('Добрый день, мне очень понравилась ваша статья');
        $this->open('/');
        $this->assertTextPresent('Добрый день, мне очень понравилась ваша статья');
        sleep(1);

        $this->click('id=Comments_News_'. $comment2->id .'_like');
        sleep(2);

        $added = false;
        if ($likes = Likes::model('Comments_News')->findByPk($comment2->id)) {
            foreach ($likes->users as $user) {
                if ($user->id == $testUser->id) {
                    $added = true;
                }
            }
        }

        $this->assertTrue($added, 'Лайк добавлен в монгу');

        $this->open('/');
        $this->assertTitle('Мои новости | Migom.by');

        $this->assertTextPresent('Добрый день, мне очень понравилась ваша статья');
        $this->assertTextPresent('Спасибо, я буду продолжать писать статьи дальше');
        $this->assertElementPresent('id=Comments_News_'. $comment->id .'_delete');
        $this->assertElementPresent('id=Comments_News_'. $comment2->id .'_delete');
        $this->click('id=Comments_News_'. $comment->id .'_delete');
        sleep(1);
        $this->click('id=Comments_News_'. $comment2->id .'_delete');
        sleep(1);

        $this->open('/');
        $this->assertTitle('Мои новости | Migom.by');

        $this->assertTextNotPresent('Добрый день, мне очень понравилась ваша статья');
        $this->assertTextNotPresent('Спасибо, я буду продолжать писать статьи дальше');
        $this->assertElementNotPresent('id=Comments_News_'. $comment->id .'_delete');
        $this->assertElementNotPresent('id=Comments_News_'. $comment2->id .'_delete');
        sleep(1);

        // умываем руки ))
        $this->assertTrue($comment->delete(), 'Удаляем первый коммент');
        $this->assertTrue($comment2->delete(), 'Удаляем второй коммент');
        $this->assertTrue($testUser->delete(), 'Удаляем пользователя');
    }
}
