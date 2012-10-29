<?php

abstract class Api extends CModel
{

    protected $_rest;
    protected $_config;
    protected $_responce;
    private $methods = array('get' => 3, 'post' => 4, 'put' => 3, 'delete' => 6);

    /**
     * @example protected function getApiTitle(){ return 'migom'; }
     * @return string Default api config name
     */
    protected function getApiTitle()
    {
        return 'migom';
    }

    public function __construct()
    {
        if (!YII::app()->hasComponent('RESTClient')) {
            throw new CHttpException(404, Yii::t('App', 'Have not the extention "RESTClient"'), 404);
        }
        $this->_rest = YII::app()->RESTClient;
    }

    public function __call($function, $args)
    {
        $error = true;
        $params = array();
        $id = null;
        foreach ($this->methods as $method => $len) {
            if (strpos($function, $method) === 0) {
                $function = substr($function, $len);
                $error = false;
                break;
            }
        }
        if ($error) {
            throw new CHttpException(404, Yii::t('App', 'REST haven`t got method {method}', array('{method}' => $function)), 404);
        }
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $params = $arg;
            } else {
                $id = $arg;
            }
        }
        $class = explode('_', get_class($this));
        $controller = array_pop($class);
        return $this->query($controller, $function, $id, $method, $params);
    }

    public function query($controller, $function = '', $id = null, $method = 'get', $params = array())
    {
        $server = $this->getApiTitle();
        $this->_rest->initialize($server);
        $params['key'] = $this->_getSuid($server);
        $uri = $this->_createUri($controller, $function, $id);
        Yii::trace(get_class($this) . '.query()', 'RESTClient');
        $responce = $this->_rest->{$method}($uri, $params, 'json');
        if($responce->content->success !== true){
            Yii::log($responce->content->message, CLogger::LEVEL_ERROR, 'api_client');
        }
        $this->_responce = $responce;
//        $this->_rest->debug();
        return $this->_format($responce);
    }

    private function _format($responce){
        $param = ApiComponent::CONTENT_RESPONCE;
        $success = ApiComponent::CONTENT_SUCCESS;
        if(isset($responce->content->$success) && $responce->content->$success === false){
            return false;
        }
        if(isset($responce->content->$param)){
            return $responce->content->$param;
        }
        return $responce;
    }

//    public function getErrors(){
//        $param = ApiComponent::CONTENT_MESSAGE;
//        if(isset($this->_responce->content->$param)){
//            return $this->_responce->content->$param;
//        }
//        return false;
//    }

    private function _getSuid($server)
    {
        $suid = yii::app()->cache->get($server . '_suid');
        if (!$suid) {
            $servers = $this->_rest->servers[$server];
            $key = $servers['key'];
            $params = null;
            $type = ApiComponent::TYPE_JSON;
            if (isset($servers['type']) && $servers['type']) {
                $type = $servers['type'];
                $params = array('type' => $type);
            }
            $responce = $this->_rest->get('auth/login/' . $key, $params, $type);
            if (!isset($responce->content->suid)) {
                echo CJSON::encode($responce);
                Yii::app()->end();
            }
            $suid = $responce->content->suid;
            yii::app()->cache->set($server . '_suid', $suid);
        }
        return $suid;
    }

    protected function _createUri($controller, $function = '', $id = null) {
        $uri = $controller;
        if ($function) {
            $uri .= '/' . $function;
        }
        if ($id) {
            $uri .= '/' . $id;
        }
        return strtolower($uri);
    }

}