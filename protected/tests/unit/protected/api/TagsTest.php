<?php
class TagsTest extends CTestCase
{

	public $user_id = 1; // ek
	
    public function testAdverts()
    {
        $adverts = Api_Adverts::model();
		$adverts = $adverts->getByUser($this->user_id, 
							array(
								'limit' => UserNews::NEWS_ON_WALL+1, 
								'offset' => 0,
								'with' => 'auction',
							));
		
		$uIds = array();

		$this->assertTrue(is_object($adverts));
		
		foreach($adverts as $adv){
			$uIds[] = $adv->user_id;
			foreach($adv->auctions as $auc){
				$uIds[] = $auc->user_id;
			}
		}
		$this->assertTrue(in_array($this->user_id, $uIds));
    }
}