<?php

class NotifyCommand extends ConsoleCommand {
    
    public $fromQueue = false;

    public function actionProductCost() {
        $aProductId = array();
        $time = time();;
        $subscribers = Notify::model('Product_Cost')->findAll();
        foreach ($subscribers as $subscriber) {
            $aProductId[$subscriber->product_id] = $subscriber->product_id;
        }
        
        $apiModel = new Api_Products();
        $minPriceResponce = $apiModel->getCosts('min', array('id' => $aProductId))->content;
        if($minPriceResponce->success !== true){
            //TODO loging
            echo "fatal: " . $minPriceResponce->message;
            Yii::app()->end();
        }
        $productForSend = array();
        $userForNotify = array();
        foreach ($minPriceResponce->products as $product) {
            foreach ($subscribers as $subscriber) {
                if($product->id == $subscriber->product_id){
                    if($subscriber->cost >= $product->cost){
                        
                        $productForSend[$subscriber->product_id] = $subscriber->product_id;
                        $userForNotify[$subscriber->user_id][$subscriber->product_id] = array('product_id' => $subscriber->product_id,
                                                                                                'cost' => $product->cost,
                                                                                                'subscriber_id' => $subscriber->id);
                    }
                }
            }
        }
        if(count($productForSend) == 0){
            echo "no notice";
            Yii::app()->end();
        }
        $productInfo = $apiModel->getInfo('title', array('id' => $productForSend))->content;
        
        if($productInfo->success !== true){
            //TODO loging
            echo "fatal: " . $productInfo->message;
            Yii::app()->end();
        }
        $productTitles = get_object_vars($productInfo->products);
        foreach ($userForNotify as $userId => $products) {
            
            $user = Users::model()->findByPk($userId);
            $mail = new Mail();
            foreach ($products as $product) {
                $mail->send($user, 'notifyProductCost', array('date' => $time, 
                                                              'cost' => $product['cost'], 
                                                              'productId' => $product['product_id'],
                                                              'productName' => $productTitles[$product['product_id']]));
                Notify::model('Product_Cost')->deleteByPk($product['subscriber_id']);
            }
        }
    }
    
    
}