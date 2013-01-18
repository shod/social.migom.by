<?php

/**
 * Notify user
 * @package api
 */
class BookmarksController extends ERestController
{

    /**
     * Add notify about change product cost
     * @param type $id - product id
     * @param float $cost - product cost
     * @throws ERestException
     */
    public function actionPostSinchronize($entity)
    {
		d($entity);
		die('ssss');
        $userId = (int) Yii::app()->request->getParam('user_id');
		$data = Yii::app()->request->getParam('data');
        Bookmarks::update($entity, $userId, $data);
        $this->render()->sendResponse(array(ERestComponent::CONTENT_SUCCESS => true));
    }

}