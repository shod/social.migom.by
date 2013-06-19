<?php

/**
 * Notify command
 * @example yiic notify productcost --models=comments_news --models=comments_news2...
 */
class NotifyCommand extends ConsoleCommand
{

	public $limit = 100;
	public $offset = null;
    public $fromQueue = false;

    public function actionProductCost()
    {
        $aProductId = array();
        $time = time();
		
		if($this->offset === null ){
			$count = Notify::model('Product_Cost')->count('is_send = 0');
			for($offset = 0; $offset < $count; $offset = $offset + $this->limit){
				exec('/h/socialmigomby/htdocs/protected/yiic notify productCost --offset=' . $offset );
			}
			return;
		}

        $subscribers = Notify::model('Product_Cost')->findAll(array('condition' => 'is_send = 0', 'limit' => $this->limit, 'offset' => $this->offset));
        foreach ($subscribers as $subscriber) {
            $aProductId[$subscriber->product_id] = $subscriber->product_id;
        }
		
        $apiModel = new Api_Products();
		$minPriceResponce = $apiModel->getCosts('min', array('id' => $aProductId));
        
		
        if (!$minPriceResponce || !is_array($minPriceResponce)) {			
            $errors = $apiModel->getErrors();
			$errors['message'] = 'noPriceResponce';
            //Yii::log(print_r($errors, true), CLogger::LEVEL_INFO);
            Yii::app()->end();
        }

        $productForSend = array();
        $userForNotify = array();
        foreach ($minPriceResponce as $product) {
            foreach ($subscribers as $subscriber) {
                if ($product->id == $subscriber->product_id) {
                    if ($subscriber->cost >= $product->cost) {

                        $productForSend[$subscriber->product_id] = $subscriber->product_id;
                        $userForNotify[$subscriber->user_id][$subscriber->product_id] = array(
                            'product_id'    => $subscriber->product_id,
                            'cost'          => $product->cost,
                            'subscriber_id' => $subscriber->id);
                    }
                }
            }
        }
        if (count($productForSend) == 0) {
            echo "no notice";
            Yii::app()->end();
        }
        $productInfo = $apiModel->getInfo('attr', array('id' => $productForSend, 'list' => array('title', 'url', 'image'), 'image_size' => 'small'));

        if (!$productInfo) {
            $errors = $apiModel->getErrors();
			$errors['message'] = 'noProductInfo';
            Yii::log(print_r($errors, true), CLogger::LEVEL_INFO);
            Yii::app()->end();
        }
        $productInfo = get_object_vars($productInfo);
        foreach ($userForNotify as $userId => $products) {
            $user = Users::model()->findByPk($userId);
            $mail = new Mail();
            foreach ($products as $product) {
                News::pushPriceDown($user, $product, $productInfo[$product['product_id']]);
                $mail->send($user, 'notifyProductCost', array(
                    'date'        => $time,
                    'cost'        => $product['cost'],
                    'productId'   => $product['product_id'],
                    'productName' => $productInfo[$product['product_id']]->title
                ));
				$subscriber = Notify::model('Product_Cost')->findByPk($product['subscriber_id']);
				$subscriber->is_send = 1;
                $subscriber->save();
            }
        }
    }
	
	public function actionProduct()
    {
        $aProductId = array();
        $time = time();
		
		if($this->offset === null ){
			$count = Notify::model('Product')->count('is_send = 0');
			for($offset = 0; $offset < $count; $offset = $offset + $this->limit){
				exec('/h/socialmigomby/htdocs/protected/yiic notify product --offset=' . $offset );
			}
			return;
		}

        $subscribers = Notify::model('Product')->findAll(array('condition' => 'is_send = 0', 'limit' => $this->limit, 'offset' => $this->offset));

		foreach ($subscribers as $subscriber) {
			//if($subscriber->product_id == 281761)
				$aProductId[$subscriber->product_id] = $subscriber->product_id;
        }
		
		$apiModel = new Api_Products();
        $minPriceResponce = $apiModel->getCosts('min', array('id' => $aProductId));

        if (!$minPriceResponce || !is_array($minPriceResponce)) {
            $errors = $apiModel->getErrors();
			$errors['message'] = 'noPriceResponce';
            //Yii::log(print_r($errors, true), CLogger::LEVEL_INFO);
            Yii::app()->end();
        }
        $productForSend = array();
        $userForNotify = array();
        foreach ($minPriceResponce as $product) {
            foreach ($subscribers as $subscriber) {
                if ($product->id == $subscriber->product_id && $product->cost > 1) {
                        $productForSend[$subscriber->product_id] = $subscriber->product_id;
                        $userForNotify[$subscriber->user_id][$subscriber->product_id] = array(
                            'product_id'    => $subscriber->product_id,
                            'cost'          => $product->cost,
                            'subscriber_id' => $subscriber->id);
                }
            }
        }
        if (count($productForSend) == 0) {
            echo "no notice";
            Yii::app()->end();
        }
        $productInfo = $apiModel->getInfo('attr', array('id' => $productForSend, 'list' => array('title', 'url', 'image'), 'image_size' => 'small'));

        if (!$productInfo) {
            $errors = $apiModel->getErrors();
			$errors['message'] = 'noProductInfo';
            Yii::log(print_r($errors, true), CLogger::LEVEL_INFO);
            Yii::app()->end();
        }
        $productInfo = get_object_vars($productInfo);
        foreach ($userForNotify as $userId => $products) {

            $user = Users::model()->findByPk($userId);
			if($user->status != 1){
				continue;
			}
            $mail = new Mail();
            foreach ($products as $product) {
                News::pushInSale($user, $product, $productInfo[$product['product_id']]);
                $mail->send($user, 'notifyProduct', array(
                    'date'        => $time,
                    'cost'        => $product['cost'],
                    'productId'   => $product['product_id'],
                    'productName' => $productInfo[$product['product_id']]->title
                ));
				$subscriber = Notify::model('Product')->findByPk($product['subscriber_id']);
				$subscriber->is_send = 1;
                $subscriber->save();
            }
        }
    }

}