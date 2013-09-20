<?php
class NewsTest extends CTestCase
{

	public $user_id = 1; // ek
	public $news_ids = array(5902, 5860, 5578, 5551, 5547, 5546, 5541);

	function setUp() {
        parent::setUp();
    }
	
    public function testNewsCount()
    {
		$news = Api_News_Author::model();
		$this->assertTrue(($news instanceof ERestDocument), 'Api_News_Author not instance of ERestDocument');
		$count = $news->count('user_id = :id', array(':id' => $this->user_id));
		$this->assertTrue(is_numeric($count), 'Not count return');
		$this->assertTrue(($count==0), 'User 1 dont write any news on migom.by');
		$count = $news->count('user_id = :id', array(':id' => 10916));
		$this->assertTrue(($count>0), 'Miroslav write many news on migom.by (more then 0)');
    }
	
	public function testNewsGetByIds(){
		// actionGetByIds
		$news = Api_News::model()->getByIds(array('ids' => $this->news_ids));
		$this->assertTrue($news->success, 'result not success');
		unset($news->success);
		$this->assertEquals(count($news), count($news), 'Count news not equals with count ids');
	}
	
	/**
     * Test migom news GetDayEvents
     * @covers NewsController::actionGetDayEvents
     * @covers Api_News
     */
	public function testNewsGetDayEvents(){
		$day_events = Api_News::model()->getDayEvents();
		$this->assertTrue(is_object($day_events), 'Api request is not a object');
		$this->assertTrue($day_events->success, 'result not success (may be we have not events now)');
		unset($day_events->success);
		foreach($day_events as $event){
			$this->assertEquals($event->public, '1', 'This new not public');
			$this->assertEquals(substr($event->start_date, 0, 10), date('Y-m-d'), 'This event not for this date');
		}
	}
	
	/**
     * Test migom news get title
     * @covers NewsController::actionGetTitle
     * @covers Api_News
     */
	public function testNewsGetTitle(){
		$title = Api_News::model()->getTitle(5902);
		$this->assertEquals($title, 'Как выбрать телевизор?', 'One of News with id = 5902 is "Как выбрать телевизор?". We get another news sample');
	}
	
	public function dataProviderNewsFindByPk(){
		$ids = array();
		foreach($this->news_ids as $id){
			$ids[] = array($id);
		}
		return $ids;
	}
	
	/**
     * Test migom news
     * @dataProvider dataProviderNewsFindByPk
     */
	public function testNewsFindByPk($id){
		$news = Api_News::model();
		$this->assertTrue(($news instanceof ERestDocument), 'Api_News not instance of ERestDocument');
		$news = $news->findByPk($id);
		$this->assertTrue(is_object($news), 'Api request is not a object');
		if($id == 5902){
			$this->assertEquals($news->title, 'Как выбрать телевизор?', 'One of News with id = 5902 is "Как выбрать телевизор?". We get another news sample');
		}
	}
}