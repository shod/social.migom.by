<?php

class AdsModule extends CWebModule
{
        private $_assetsUrl;

        public $ipFilters;
        public $urlRules = array(
            'ads/comments/<model:(news)>'=>'ads/comments/list',
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