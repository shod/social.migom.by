<?php
class ArticleTest extends CTestCase
{

	public $user_id = 1; // ek
	public $article_ids = array(4663, 4689, 4655, 4667, 4644, 4621, 4618);

	function setUp() {
        parent::setUp();
    }
	
	public function dataProviderArticleFindByPk(){
		$ids = array();
		foreach($this->article_ids as $id){
			$ids[] = array($id);
		}
		return $ids;
	}
	
	/**
     * Test migom news
     * @dataProvider dataProviderArticleFindByPk
     */
	public function testArticleFindByPk($id){
		$article = Api_Article::model();
		$this->assertTrue(($article instanceof ERestDocument), 'Api_Article not instance of ERestDocument');
		$article = $article->findByPk($id);
		$this->assertTrue(is_object($article), 'Api request is not a object');
		if($id == 4689){
			$this->assertEquals($article->title, 'Обзор гарнитуры SteelSeries Flux In-Ear Gaming Headset', 'One of Article with id = 5902 is "Как выбрать телевизор?". We get another news sample');
		}
	}
	
	public function testArticleCount(){
		
		// count
		$article = Api_Article_Author::model();
		$this->assertTrue(($article instanceof ERestDocument), 'Api_Article_Author not instance of ERestDocument');
		$count = $article->count('user_id = :id', array(':id' => $this->user_id));
		$this->assertTrue(is_numeric($count), 'Not count return');
		$this->assertTrue(($count==0), 'User 1 dont write any article for migom.by');
		$count = $article->count('user_id = :id', array(':id' => 10916));
		$this->assertTrue(($count>0), 'Miroslav write many articls for migom.by (more then 0)');
	}
	
	public function testArticleGetByIds(){
		$article = Api_Article::model()->getByIds(array('ids' => $this->article_ids));
		$this->assertTrue($article->success, 'result not success');
		unset($article->success);
		$this->assertEquals(count($article), count($article), 'Count article not equals with count ids');
	}
	
	/**
     * Test migom news get title
     * @covers ArticleController::actionGetDayEvents
     * @covers Api_Article
     */
	public function testArticleGetDayEvents(){
		$day_events = Api_Article::model()->getDayEvents();
		$this->assertTrue(is_object($day_events), 'Api request is not a object');
		$this->assertTrue($day_events->success, 'result not success(may be we have not events now (it`s not a BUG!!))');
		unset($day_events->success);
		foreach($day_events as $event){
			$this->assertEquals($event->public, '1', 'This article not public');
			$this->assertEquals(substr($event->start_date, 0, 10), date('Y-m-d'), 'This event not for this date');
		}
	}
}