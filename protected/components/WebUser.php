<?php
class WebUser extends CWebUser {
    private $_model = null;
    private $_id = null;

    private function _getModel()
    {
        if (!$this->isGuest && $this->_model === null && $this->id)
        {
            $this->_model = Users::model()->findByPk($this->id /*array('select' => 'role')*/);
        }
        return $this->_model;
    }
    
    public function login($identity, $duration)
    {
        $this->id = $identity->getId();
        $user = $this->_getModel();
        
        // return role name
        $this->setState('name', $user->login);
        $this->setState('role', Users::$roles[$user->role]);

        parent::login($identity, $duration);
    }
+    
+    public function getReturnUrl($defaultUrl=null)
+    {
			if(isset($_SERVER['HTTP_REFERER'])){
				$referer = $_SERVER['HTTP_REFERER'];
			} else {
				$referer '/user/';
			}
+            return $this->getState('__returnUrl', $defaultUrl===null ? $_SERVER['HTTP_REFERER'] : CHtml::normalizeUrl($defaultUrl));
+    }

}