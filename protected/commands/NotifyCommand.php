<?php

class NotifyCommand extends ConsoleCommand {
    
    public function actionProductCost() {
        
        $aProductId = array();
        
        $subscribers = Notify::model('Product_Cost')->findAll();
        foreach ($subscribers as $subscriber) {
            $aProductId[$subscriber->product_id] = $subscriber->product_id;
        }
        
        $apiModel = new Api_Product();
        $minPriceResponce = $apiModel->getCosts('min', $aProductId);
        $aProductCosts = $minPriceResponce->content->products;
        foreach ($aProductCosts as $product) {
            foreach ($subscribers as $subscriber) {
                if($product->id == $subscriber->product_id){
                    if($subscriber->cost >= $product->cost){
                        $this->_sendNotify($subscriber->user_id, array('cost' => $product->cost, 
                                                                       'productName' => '',
                                                                       'ProductUrl' => ''));
                        Notify::model('Product_Cost')->deleteByPk($subscribers->id);
                    }
                }
            }
        }
    }
    
    private function _sendNotify($user_id, $params){
        
    }
    
    
}