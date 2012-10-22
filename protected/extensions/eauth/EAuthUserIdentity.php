<?php
/**
 * EAuthUserIdentity class file.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

/**
 * EAuthUserIdentity is a base User Identity class to authenticate with EAuth.
 * @package application.extensions.eauth
 */
class EAuthUserIdentity extends CUserIdentity {

	const ERROR_NOT_AUTHENTICATED           = 3;
        const ERROR_USER_NOT_REGISTERED         = 4;

	/**
	 * @var EAuthServiceBase the authorization service instance.
	 */
	protected $service;

	/**
	 * @var string the unique identifier for the identity.
	 */
	protected $id;

	/**
	 * @var string the display name for the identity.
	 */
	protected $name;
        
        protected $attributes;
        
        protected $soc_id;
        
        public $addNewSocial = false;

	/**
	 * Constructor.
	 * @param EAuthServiceBase $service the authorization service instance.
	 */
	public function __construct($service) {
		$this->service = $service;
	}

	/**
	 * Authenticates a user based on {@link service}.
	 * This method is required by {@link IUserIdentity}.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
		if ($this->service->isAuthenticated) {
			$this->name = $this->service->getAttribute('name');

			$this->setState('id', $this->id);
			$this->setState('name', $this->name);
//			$this->setState('service', $this->service->serviceName);

			// You can save all given attributes in session.
//			$this->attributes = $this->service->getAttributes();
//			$session = Yii::app()->session;
//			$session['eauth_attributes'][$this->service->serviceName] = $attributes;

			$this->errorCode = self::ERROR_NONE;
		}
		else {
			$this->errorCode = self::ERROR_NOT_AUTHENTICATED;
                        return $this->errorCode;
		}
                
                if(!$this->errorCode && $this->service->getAttribute('soc_id')){
                    $user = false;
                    $criteria = new CDbCriteria;
                    $criteria->compare('soc_id', $this->service->getAttribute('soc_id'));
                    $criteria->limit = 1; //TODO реализовать выбор социалки для входа
                    $provider = Users_Providers::model($this->service->serviceName);
                    $provider = $provider->find($criteria);
                    if($provider){
                        $user = $provider->user;
                    } elseif($this->service->getAttribute('email')) {
                        $user = Users::model()->find(array('email' => $this->service->getAttribute('email')));
                        if($user){
                            $this->addNewSocial = true;
                        }
                    }
                    if(!$user){
                        $this->errorCode = self::ERROR_USER_NOT_REGISTERED;
                    } else {
                        // В качестве идентификатора будем использовать id, а не username,
                        // как это определено по умолчанию. Обязательно нужно переопределить
                        // метод getId(см. ниже).
                        $this->id = $user->id;
                        // Используется как Yii::app()->user->name.
                        $this->username = $user->login;
                        $this->errorCode = self::ERROR_NONE;
                    }
                }
                
		return !$this->errorCode;
	}
        
        public function getAttributes(){
            return $this->service->getAttributes();
        }
        
        public function getAttribute($name){
            return $this->service->getAttribute($name);
        }
        
        public function getProviderName(){
            return $this->service->getProviderName();
        }

	/**
	 * Returns the unique identifier for the identity.
	 * This method is required by {@link IUserIdentity}.
	 * @return string the unique identifier for the identity.
	 */
	public function getId() {
		return $this->id;
	}
        
        public function setId($id) {
		return $this->id = $id;
	}
}