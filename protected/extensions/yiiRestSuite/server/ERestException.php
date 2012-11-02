<?php

class ERestException extends CComponent {

    public function init(){}

    /**
     * @param string $message
     */
        public function __construct($message) {
            Yii::app()->controller->module->render->sendResponse(array('message'=>$message, ERestComponent::CONTENT_SUCCESS => false));
            Yii::log($message, CLogger::LEVEL_ERROR, 'api_server');
            Yii::app()->end();
        }

}
