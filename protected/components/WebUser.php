<?php

class WebUser extends CWebUser {

    public $defaultRole;
    private $_model = null;
    private $_id = null;

    private function _getModel() {
        if (!$this->isGuest && $this->_model === null && $this->id) {
            $this->_model = Users::model()->findByPk($this->id /* array('select' => 'role') */);
        }
        return $this->_model;
    }

    /**
     * Returns the unique identifier for the user (e.g. username).
     * This is the unique identifier that is mainly used for display purpose.
     * @return string the user name. If the user is not logged in, this will be {@link guestName}.
     */
    public function getRole() {
        if (($name = $this->getState('__role')) !== null)
            return $name;
        else
            return $this->defaultRole;
    }

    /**
     * Sets the unique identifier for the user (e.g. username).
     * @param string $value the user name.
     * @see getName
     */
    public function setRole($value) {
        $this->setState('__role', $value);
    }

    public function login($identity, $duration) {
        $this->id = $identity->getId();
        $user = $this->_getModel();

        // return role name
//        $this->setState('name', $user->login);
//        $this->setState('role', Users::$roles[$user->role]);
        $this->setRole(Users::$roles[$user->role]);
d(Users::$roles[$user->role]);
die;
        parent::login($identity, $duration);
    }

}