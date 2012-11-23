<?php

class UsersTest extends CTestCase
{

    public $fixtures=array(
        'users'=>'Users',
        'profile'=>'Users_Profile',
    );

    public function testValidateAndDBUsersProfile()
    {
        new Users_Profile();
        // insert a comment in pending status
        $user=new Users;
        $user->setAttributes(array(
            'login'         =>  'testUser',
            'password'      =>  123456,
            'role'          =>  array_search('user', Users::$roles),
            'email'         =>  'test@emaisdfl.com',
            'status'        =>  array_search('active', Users::$statuses),
            'date_add'      =>  time(),
            'date_edit'     =>  time(),
        ),false);

        $this->assertTrue(!$user->save());
        $email = 'test'.rand(0, 9999999).'@mail.com';
        $user->email = $email;
        $this->assertTrue($user->validate(), 'Валидация пользователя не прошла');

        $this->assertTrue($user->save());

        $user = Users::model()->findByPk($user->id);
        $this->assertTrue($user instanceof Users);

        $user->profile = new Users_Profile();
        $user->profile->setAttributes(array(
            'user_id'   =>  $user->id,
            'name'      =>  $user->login . 'name',
            'surname'   =>  $user->login . 'surname',
            'city_id'   =>  Regions::model()->find('name = "Минск"')->id,
            'sex'       =>  array_search('male', Users_Profile::$sexs),
            'birthday'  =>  '1955-02-25',
        ),false);

        $this->assertTrue($user->profile->save(), print_r($user->profile->getErrors(),1));

        $this->assertTrue($user->delete());
        $this->assertTrue(!Users_Profile::model()->find('user_id = '.$user->id));
    }
}