<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
	
		if(!$aStatistics = Yii::app()->cache->get('ads_statistics')){
			$aStatistics = array(
				'commentsNews' 		=> Comments_News::model()->count(),
				'commentsArticle' 	=> Comments_Article::model()->count(),
				'commentsProduct' 	=> Comments_Product::model()->count(),
				'usersActive' 		=> Users::model()->count('status = 1'),
				'usersNoActive' 	=> Users::model()->count('status != 1'),
				'afterBuildSocial' 	=> Users::model()->count('date_add > 1359072000'), // 25.01.2013
				'messages' 			=> Messages_Text::model()->count(),
				'notifyProductCost' => Notify_Product_Cost::model()->count(),
				'notifyProduct' 	=> Notify_Product::model()->count(),
				'bookmark' 			=> Bookmarks_Bookmark::model()->count(),
			);
			Yii::app()->cache->set('ads_statistics', $aStatistics, 3600);
		} 
		
				
				
		$this->render('index', array('statistics' => $aStatistics));
	}
}