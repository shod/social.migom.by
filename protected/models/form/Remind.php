<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class Form_Remind extends CFormModel
{
	public $email;
    public $userModel;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
            array('email', 'remindEmailCheck'),
		);
	}

    public function remindEmailCheck($attribute, $params){
        $this->userModel = Users::model()->findByAttributes(array('email'=>$this->email));
        if(!$this->userModel){
            $this->addError('email', Yii::t('Site', 'Email введен не верно'));
        }
    }
}
