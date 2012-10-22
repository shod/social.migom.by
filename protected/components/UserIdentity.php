<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        const ERROR_USER_BLOCKED = 3;
	protected $id;
        protected $firstTime = false;
        
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
            $user = Users::model()->find('LOWER(email)=?', array(strtolower($this->username)));
            if($user===null)
            {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            }
            elseif(trim($this->password)!==$user->password) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } elseif(Users::$statuses[$user->status] == 'ban') {
                $this->errorCode = self::ERROR_USER_BLOCKED;
            } else {
                $this->id = $user->id;
                if(!$this->firstTime && Users::$statuses[$user->status] == 'noactive'){
                    $user->status = array_search('active', Users::$statuses);
                    $user->save();
                }
                
                $this->username = $user->login;
                $this->errorCode = self::ERROR_NONE;
            }
            return !$this->errorCode;
	}
        
        public function getId(){
            return $this->id;
        }
        
        public function setFirstTime(){
            $this->firstTime = true;
        }
}