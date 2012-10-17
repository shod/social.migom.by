<?php

/**
 * Notify user
 * @package api
 */
class NotifyController extends ApiController
{

    public function actionPostProductCost($id)
    {
        $model = new Notify_Product_Cost();
        $model->product_id = $id;
        $model->cost = (float) Yii::app()->request->getParam('cost');
        $model->user_id = (int) Yii::app()->request->getParam('user_id');
        try {
            $model->save();
        } catch (Exception $exc) {
            throw new ApiException(Yii::t('Notify', $exc->getMessage()));
        }
        $this->render()->sendResponse(array(ApiComponent::CONTENT_SUCCESS => true));
    }

}