<?php

/**
 * Notify user
 * @package api
 */
class NotifyController extends ERestController
{

    const EXCEPTION_COST_MUST_BE_MORE_ZERO = 'Cost must be more zero';
    const EXCEPTION_USER_IS_NOT_EXIST = 'User is not exist';


    /**
     * Add notify about change product cost
     * @param type $id - product id
     * @param float $cost - product cost
     * @throws ERestException
     */
    public function actionPostProductCost($id)
    {
        $userId = Yii::app()->request->getParam('user_id', 0, 'int');
        $cost = Yii::app()->request->getParam('cost', 0, 'float');
		$email = Yii::app()->request->getParam('email', 0, 'str');
        if(!$user = Users::model()->findByPk($userId)){
            throw new ERestException(Yii::t('Notify', self::EXCEPTION_USER_IS_NOT_EXIST));
        }

        if($cost == 0){
            throw new ERestException(Yii::t('Notify', self::EXCEPTION_COST_MUST_BE_MORE_ZERO));
        }

        $model = new Notify_Product_Cost();
        $model->product_id = (int)$id;
        $model->cost = (float) Yii::app()->request->getParam('cost');
        $model->user_id = $userId;
		$model->created_at = time();
		if(!$user->email || $user->status == 2){
			$user->email = $email;

			if($user->validate()){
				$user->sendEmailConfirm();
				$user->save();
			} else {
				//$this->render()->sendResponse(array(ERestComponent::CONTENT_SUCCESS => false, 'message' => print_r($user->getError('email'),1)));
				//return;
			}
		}
        try {
            $model->insertIgnore();
        } catch (Exception $exc) {
            throw new ERestException(Yii::t('Notify', $exc->getMessage()));
        }
        $this->render()->sendResponse(array(ERestComponent::CONTENT_SUCCESS => true));
    }
	
	/**
     * Add notify about change product cost
     * @param type $id - product id
     * @param float $cost - product cost
     * @throws ERestException
     */
    public function actionPostProduct($id)
    {
        $userId = Yii::app()->request->getParam('user_id', 0, 'int');
		$email = Yii::app()->request->getParam('email', 0, 'str');
        if(!$user = Users::model()->findByPk($userId)){
            throw new ERestException(Yii::t('Notify', self::EXCEPTION_USER_IS_NOT_EXIST));
        }
		if(!$user->email || $user->status == 2){
			$user->email = $email;

			if($user->validate()){
				$user->sendEmailConfirm();
				$user->save();
			} else {
				//$this->render()->sendResponse(array(ERestComponent::CONTENT_SUCCESS => false, 'message' => print_r($user->getError('email'),1)));
				//return;
			}
		}

		$model = new Notify_Product();
		$model->product_id = (int)$id;
        $model->user_id = $userId;
		$model->created_at = time();
        try {
            $model->insertIgnore();
        } catch (Exception $exc) {
            throw new ERestException(Yii::t('Notify', $exc->getMessage()));
        }
        $this->render()->sendResponse(array(ERestComponent::CONTENT_SUCCESS => true));
    }

}