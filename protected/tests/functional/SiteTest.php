<?php

class SiteTest extends WebTestCase
{

        public function testRegistration()
	{
            $pass = 111111;

            $this->open('/login');
            $this->assertElementPresent('name=RegistrationForm[email]', 'field to enter an email is present');

            $rn = rand(0, 999999);
            $email = "tester{$rn}@example.com";
            $this->type('name=RegistrationForm[email]', $email);

            $this->check('id=RegistrationForm_agree');
            $this->clickAndWait("//input[@src='/images/reg_btn.gif']");
            $this->assertTextPresent('Профиль');

            $this->clickAndWait("//a[@href='/user/edit']");
            $this->assertElementPresent('id=Users_password');
            $this->assertElementPresent('id=Users_repassword');

            $this->type('id=Users_password', $pass);
            $this->type('id=Users_repassword', $pass . "_fail");
            $this->click("//input[@src='/images/login_btn.gif']");
            sleep(1);
            $this->assertTextPresent('Write right pass');

            $this->type('id=Users_repassword', $pass);
            $this->clickAndWait("//input[@src='/images/login_btn.gif']");
            $this->assertTextNotPresent('Write right pass');

            $this->open('/logout');
            $this->assertTextPresent('Guest');

            $this->open('/login');
            $this->type('id=LoginForm_email', $email);
            $this->type('id=LoginForm_password', $pass . '_fail');
            $this->click("//input[@src='/images/login_btn.gif']");
            sleep(1);
            $this->assertTextPresent('Incorrect email or password');

            $this->type('id=LoginForm_email', $email);
            $this->type('id=LoginForm_password', $pass);
            $this->clickAndWait("//input[@src='/images/login_btn.gif']");
	}

}
