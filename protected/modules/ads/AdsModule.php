<?php

class AdsModule extends CWebModule
{
        private $_assetsUrl;

        public $ipFilters;
        public $urlRules = array(
<<<<<<< HEAD:protected/modules/ads/AdsModule.php
            'ads/comments/<model:(news|article)>'=>'ads/comments/list',
			'ads/<controller:\w+>/<action:\w+>' => 'ads/<controller>/<action>',
=======
            'ads/comments/<model:(news|article|product)>'=>'ads/comments/list',
>>>>>>> 087e74ef7fc8ff38786d40a9714070c3906d6412:protected/modules/ads/AdsModule.php
        );

        public function getAssetsUrl()
        {
            if ($this->_assetsUrl === null)
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('ads.assets') );
            return $this->_assetsUrl;
        }

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'ads.models.*',
			'ads.components.*',
            'ads.services.*',
			'core.widgets.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
