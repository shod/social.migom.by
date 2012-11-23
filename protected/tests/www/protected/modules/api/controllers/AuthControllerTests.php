<?php

class AuthControllerTest extends CTestCase {

    function setUp() {
        parent::setUp();
    }

    /**
     * Test server auth
     * @covers AuthController::actionLogin
     * @covers AuthApi
     * @dataProvider dataProviderLogin
     */
    function testLogin($key, $type, $code, $suid) {
       $model = new AuthApi();
       $responce = $model->getLogin($key, array('type' => $type));
       try {
           $this->assertEquals($code, $responce->code);
       } catch (Exception $exc) {
           $this->fail($responce->content->message . ' | ' . $exc->getMessage());
       }
       
       $this->assertEquals($suid, $responce->content->suid);
    }
    
    /**
     * Test server auth with POST method
     * @covers AuthController::actionLogin
     * @covers AuthApi
     */
    function testLoginPost() {
       $model = new AuthApi();
       $responce = $model->postLogin(Yii::app()->getParams()->api['key']);
       $this->assertEquals(ApiComponent::STATUS_NOT_FOUND, $responce->code);
       
    }
    
    /**
     * @todo Сделать проверку для XML
     * @example array('key', 'type', responce_code, 'suid')
     * @return array
     */
    function dataProviderLogin(){
        $suid    = Yii::app()->getParams()->api['suid'];
        $siteKey = Yii::app()->getParams()->api['key'];
        
        return array(
            'bad request'            => array(null,       null,         ApiComponent::STATUS_NOT_FOUND, null),
            'fail auth key'          => array('fail_key', null,         ApiComponent::STATUS_BAD_REQUEST, null),
            'ok auth'                => array($siteKey,   null,         ApiComponent::STATUS_OK,          $suid),
            'ok auth with json type' => array($siteKey,   'json',       ApiComponent::STATUS_OK,          $suid),
            'ok auth with fail type' => array($siteKey,   'fail_type',  ApiComponent::STATUS_BAD_REQUEST, null),
//            array('test', 'xml', 200),
            );
    }
    
   
}
