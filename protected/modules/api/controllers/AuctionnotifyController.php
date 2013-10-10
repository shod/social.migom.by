<?php

/**
 * Work with user comment
 * @package api
 */
class AuctionNotifyController extends ERestController
{
	
	public function actionPostNew()
    {
		$auction = Yii::app()->request->getParam('auction');
		$advert = Yii::app()->request->getParam('advert');
		
		Mongo_News::pushAuction($advert, $auction);
		
		Yii::app()->end();
    }
}