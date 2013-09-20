<?php
class ProductTest extends CTestCase
{
	public $productsIds = array(
		4844, 
		17179, 
		17367, 
		35917, 
		47489, 
		53746, 
		56201, 
		58305, 
		60280, 
		62034, 
		65210, 
		66358, 
		67761, 
		378313, 
		376258, 
		323985,
	);

	public function dataProvider(){
		$returnData = array();
		foreach($this->productsIds as $id){
			$returnData[] = array($id);
		}
		return $returnData;
	}

	/**
     * Test migom products
	 * @covers ProductsController::actionGetCost
	 * @dataProvider dataProvider
     */
	public function testGetCosts($id){
		$api = Api_Product::model();
		$this->assertTrue(($api instanceof ERestDocument), 'Api_Product not instance of ERestDocument');
		$apiRes = $api->getCosts('min', array('id' => array($id)));
		$this->assertTrue(is_array($apiRes), 'return not array!!');
		if(count($apiRes)){
			$apiRes = array_pop($apiRes);
			$maxCost = array_pop($api->getCosts('max', array('id' => array($id))));
			$avgCost = array_pop($api->getCosts('avg', array('id' => array($id))));
			$this->assertEquals($apiRes->id, $maxCost->id);
			$this->assertEquals($apiRes->id, $avgCost->id);
			$this->assertTrue(($apiRes->cost <= $maxCost->cost));
			$this->assertTrue(($apiRes->cost <= $avgCost->cost));
			$this->assertTrue(($maxCost->cost >= $avgCost->cost));
		}
	}
	
	/**
     * Test migom products
	 * @covers ProductsController::actionGetInfo(string type)
	 * @dataProvider dataProvider
     */
	public function testGetInfo($id){
		$api = Api_Product::model();
		$this->assertTrue(($api instanceof ERestDocument), 'Api_Product not instance of ERestDocument');
		$apiRes = $api->getInfo('title', array('id' => $id));
		$this->assertTrue(is_object($apiRes), 'Request is not a object');
		$this->assertTrue(isset($apiRes->$id->title), 'Title not isset');
		$this->assertTrue(is_string($apiRes->$id->title), 'Title is not a string');
		
		// ATTRIBUTES
		$apiRes = $api->getInfo('attr', array('id' => $id, 'image_size' => 'small', 'list' => array('image', 'url', 'title', 'id', 'cost', 'section')));
		$this->assertTrue(is_object($apiRes), 'Request is not a object');
		$this->assertTrue(isset($apiRes->$id->title), 'Title not isset');
		$this->assertTrue(is_string($apiRes->$id->title), 'Title is not a string');
		$success = @getimagesize($apiRes->$id->image);
		$this->assertTrue(($success !== false), 'File not fount or file is not image. File: '.$apiRes->$id->image);
		$urlValidator = new CUrlValidator();
		$this->assertTrue(($urlValidator->validateValue($apiRes->$id->url) !== false), 'This is not a url');
		$this->assertEquals($id, $apiRes->$id->id, 'This is not a url');
	}
}