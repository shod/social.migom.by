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
                Mongo_News::pushPriceDown($user, $product, $productInfo[$product['product_id']]);
                $mail->send($user, 'notifyProductCost', array(
                    'date'        => $time,
                    'cost'        => $product['cost'],
                    'productId'   => $product['product_id'],
                    'productName' => $productInfo[$product['product_id']]->title
                ));
				$subscriber = Notify::model('Product_Cost')->findByPk($product['subscriber_id']);
				$subscriber->is_send = 1;
                $subscriber->save();
				$sql = "UPDATE `notify_product_cost` SET `is_send`='1' WHERE (`id`='{$product['subscriber_id']}')";
				Yii::app()->db->createCommand($sql)->execute();
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
                Mongo_News::pushInSale($user, $product, $productInfo[$product['product_id']]);
                $mail->send($user, 'notifyProduct', array(
                    'date'        => $time,
                    'cost'        => $product['cost'],
                    'productId'   => $product['product_id'],
                    'productName' => $productInfo[$product['product_id']]->title
                ));
				$subscriber = Notify::model('Product')->findByPk($product['subscriber_id']);
				$subscriber->is_send = 1;
                $subscriber->save();
				
				$sql = "UPDATE `notify_product` SET `is_send`='1' WHERE (`id`='{$product['subscriber_id']}')";
				Yii::app()->db->createCommand($sql)->execute();
				
            }
        }
    }
	
	public function actionNews()
    {
        $subscribers = Notify::model('News')->findAll(array('condition' => 'is_send = 0', 'limit' => $this->limit, 'offset' => $this->offset));
	    
        foreach ($subscribers as $subscriber) {

            $user = Users::model()->findByPk($subscriber->user_id);
			if($user->status != 1){
				continue;
			}
			$criteria = new EMongoCriteria();
			$criteria->addCond('user_id', 'equals', $user->id);
			$news     = Mongo_News::model()->find($criteria);
			
			if($news){
				if(isset($news->disable_notify['weekly_digest'])){
					continue;
				}
			}
			
			$news = array();
			$news[] = array(
							'type' => 'news',
							'id' => 10131,
							'link' => 'http://www.migom.by/news/kakim_budet_iphone_7/#wdt1',
							'title' => 'Каким будет iPhone 7?',
							'created_at' => time(),
							'image' => 'http://static.migom.by/img/news/' . 10131 . '/main-medium.jpg',
						);
			$news[] = array(
							'type' => 'news',
							'id' => 10102,
							'link' => 'http://www.migom.by/news/chto_ne_tak_s_iphone_6s/#wdt1',
							'title' => 'Что не так с iPhone 6S?',
							'created_at' => time(),
							'image' => 'http://static.migom.by/img/news/' . 10102 . '/main-medium.jpg',
						);
			$news[] = array(
							'type' => 'news',
							'id' => 10076,
							'link' => 'http://www.migom.by/news/chto_delat_s_sim-kartoy_esli_kupil_noviy_smartfon_i_v_drugih_sluchayah/#wdt1',
							'title' => 'Что делать с сим-картой, если купил новый смартфон и в других случаях',
							'created_at' => time(),
							'image' => 'http://static.migom.by/img/news/' . 10076 . '/main-medium.jpg',
						);
			$news[] = array(
							'type' => 'news',
							'id' => 10066,
							'link' => 'http://www.migom.by/news/top-5_samih_proizvoditelnih_smartfonov_iz_kataloga_migomby/#wdt1',
							'title' => 'Топ 5 самых производительных смартфонов',
							'created_at' => time(),
							'image' => 'http://static.migom.by/img/news/' . 10066 . '/main-medium.jpg',
						);
			$news[] = array(
							'type' => 'news',
							'id' => 10070,
							'link' => 'http://www.migom.by/news/v_minske_otkrilsya_perviy_samsung_servis_plaza/#wdt1',
							'title' => 'В Минске открылся первый «Samsung Сервис Плаза»',
							'created_at' => time(),
							'image' => 'http://static.migom.by/img/news/' . 10070 . '/main-medium.jpg',
						);
			$news[] = array(
							'type' => 'news',
							'id' => 10144,
							'link' => 'http://www.migom.by/news/top-4_android-prilogeniya_dlya_slegeniya_za_kursom_valyut_v_belarusi/#wdt1',
							'title' => 'ТОП-4 Android-приложения для слежения за курсом валют в Беларуси',
							'created_at' => time(),
							'image' => 'http://static.migom.by/img/news/' . 10144 . '/main-medium.jpg',
						);
			
		
			
            $mail = new Mail();
			$mail->send($user, 'weeklyDigets', array('news_l1' => $news), true);
			
			$subscriber = Notify::model('News')->findByPk($subscriber->id);
			$subscriber->is_send = 1;
			$subscriber->save();
        }
    }
	
	public function actionNews2()
    {
		
        $subscribers = Notify::model('News')->findAll(array('condition' => 'is_send = 0 and message_text_id = 2222', 'limit' => $this->limit, 'offset' => $this->offset));
	    $i =0;
        foreach ($subscribers as $subscriber) {
			var_dump($i++);
            $user = Users::model()->findByPk($subscriber->user_id);
			if($user->status != 1){
				continue;
			}
			$criteria = new EMongoCriteria();
			$criteria->addCond('user_id', 'equals', $user->id);
			$news     = Mongo_News::model()->find($criteria);
			
			if($news){
				if(isset($news->disable_notify['weekly_digest'])){
					continue;
				}
			}
			
			$news = array(
				array('id' => 10259, 'title' => 'Что будет с Uber в Беларуси?', 'link'=>'http://www.migom.by/news/chto_budet_s_uber_v_belarusi/', 'descr' => ''),
				array('id' => 10257, 'title' => 'Что лучше купить для прослушивания музыки?', 'link'=>'http://www.migom.by/news/chto_luchshe_kupit_dlya_proslushivaniya_muziki/', 'descr' => ''),
				array('id' => 10252, 'title' => 'Какие необычные материалы используются при изготовлении смартфонов?', 'link'=>'http://www.migom.by/news/kakie_neobichnie_materiali_ispolzuyutsya_pri_izgotovlenii_smartfonov/', 'descr' => ''),
				array('id' => 10251, 'title' => 'Что лучше для дополнительной защиты смартфона – пленка или стекло?', 'link'=>'http://www.migom.by/news/chto_luchshe_dlya_dopolnitelnoy_zashchiti_smartfona__plenka_ili_steklo/', 'descr' => ''),
				array('id' => 10250, 'title' => 'Идеальное селфи, безопасные для зрения смартфоны и другие новинки недели', 'link'=>'http://www.migom.by/news/idealnoe_selfi_bezopasnie_dlya_zreniya_smartfoni_i_drugie_novinki_nedeli/', 'descr' => ''),
				array('id' => 10242, 'title' => 'Флагман Samsung Galaxy S7 будет на 10% дешевле своего предшественника', 'link'=>'http://www.migom.by/news/flagman_samsung_galaxy_s7_budet_na_10_deshevle_svoego_predshestvennika/', 'descr' => ''),
			);
		
			
            $mail = new Mail();
			$mail->send($user, 'weeklyDigets', array('news_l2' => $news), true);
			
			$subscriber = Notify::model('News')->findByPk($subscriber->id);
			$subscriber->is_send = 1;
			$subscriber->save();
        }
    }
	
	
	public function actionNews3()
    {
		
        $subscribers = Notify::model('News')->findAll(array('condition' => 'is_send = 0 and message_text_id = 3', 'limit' => $this->limit, 'offset' => $this->offset));
	    $i =0;
        foreach ($subscribers as $subscriber) {
			var_dump($i++);
			
            $user = Users::model()->findByPk($subscriber->user_id);
			if($user->status != 1){
				continue;
			}
			$criteria = new EMongoCriteria();
			$criteria->addCond('user_id', 'equals', $user->id);
			$news     = Mongo_News::model()->find($criteria);
			
			if($news){
				if(isset($news->disable_notify['weekly_digest'])){
					continue;
				}
			}
			
			
			
			$news = array(
				array('id' => 10383, 'title' => 'Что выбрать в подарок к Новому году в китайском интернет-магазине?', 
				'link'=>'http://www.migom.by/news/chto_vibrat_v_podarok_k_novomu_godu_v_kitayskom_internet-magazine/', 
				'descr' => 'Ассортимент отечественных магазинов не всегда позволяет выбрать достойный подарок к Новому году. Если вы решите удивить друзей и близких оригинальным презентом, можете попробовать что-нибудь экзотическое из китайских интернет-магазинов.'),
				
				array('id' => 10387, 'title' => 'Мобильные тренды 2016. Чего ждать от новых смартфонов?', 
				'link'=>'http://www.migom.by/news/mobilnie_trendi_2016_chego_gdat_ot_novih_smartfonov/', 
				'descr' => 'Совсем скоро наступит 2016 год, а вместе с ним в IT-индустрии и, в частности на мобильном рынке наверняка сформируются новые тренды. Попытаемся взглянуть в ближайшее будущее и предугадать, что именно станет модным в смартфонной отрасли на основе уже наметившихся тенденций.'),
				
				array('id' => 10405, 'title' => 'Смартфон Oukitel K10000 с аккумулятором на 10 000 мАч продается за 200 долларов', 
				'link'=>'http://www.migom.by/news/smartfon_oukitel_k10000_s_akkumulyatorom_na_10_000_mach_prodaetsya_za_200_dollarov/', 
				'descr' => 'Производитель долгоиграющих и неубиваемых гаджетов – компания Oukitel – начала продажи своего смартфона К10000 с батареей на 10 000 мАч. Приобрести новинку можно за 200 долларов.'),
				
				array('id' => 10402, 'title' => '8 самых нестандартных применений смартфона', 
				'link'=>'http://www.migom.by/news/8_samih_nestandartnih_primeneniy_smartfona/', 
				'descr' => 'Известно, что современные смартфоны способны выполнять большое количество различных операций, однако порой мы даже не догадываемся о том, на что еще способны наши мобильные устройства и как их можно применять, помимо звонков, веб-серфинга, съемок, игр и потребления медиконтента.'),
				
				array('id' => 10404, 'title' => 'Чего ждать от компании Nokia в 2016 году?', 
				'link'=>'http://www.migom.by/news/chego_gdat_ot_kompanii_nokia_v_2016_godu/', 
				'descr' => 'Легендарная компания Nokia, как известно, ушла с мобильного рынка в 2014 году, подписав всем известный договор с Microsoft. В скором времени, впрочем, все может измениться, так как соглашение с американским софтверным гигантом, по условиям которого финский вендор не имеет права производить смартфоны, истекает в следующем году.'),
				
				array('id' => 10386, 'title' => 'В вагонах минского метро открылась «Мобильная библиотека»', 
				'link'=>'http://www.migom.by/news/v_vagonah_minskogo_metro_otkrilas_mobilnaya_biblioteka/', 
				'descr' => 'В минском метро начала работу «Мобильная библиотека МТС», благодаря которой любой желающий может бесплатно ознакомиться с лучшими произведениями художественной и прикладной литературы. Всего в столичной подземке появится десять вагонов-библиотек, чтобы все больше минчан приобщились к хорошим книгам.'),
			);
			
            $mail = new Mail();
			$mail->send($user, 'weeklyDigets', array('news_l3' => $news), true);
			
			$subscriber = Notify::model('News')->findByPk($subscriber->id);
			$subscriber->is_send = 1;
			$subscriber->save();
        }
    }
	
	
	public function actionNews666()
    {
		$model = Api_Mail_Digest::model();
		//$model->debug = true;
		//var_dump($model);
		$data = $model->findAll();
		$news = array();
		$news_other = array();
		$title = '';
		foreach($data as $row){
			if($row->type == 'main'){
				$news[] =	array(
					'id' 	=> $row->news_id, 
					'title' => $row->ancor, 
					'link'	=> $row->url, 
					'descr' => $row->descr);
			}elseif($row->type == 'title'){
				$title = $row->ancor;
			}elseif($row->type == 'rec'){
				$news_other[] =	array(
					'id' 	=> $row->news_id, 
					'title' => $row->ancor, 
					'link'	=> $row->url, 
					'descr' => $row->descr);
			}
		}
		
		$sql = "delete from notify_news where message_text_id != 3;";
		Yii::app()->db->createCommand($sql)->execute();
		$sql = "insert into notify_news (message_text_id, user_id, is_send, created_at)
				select 2222,id,0, CURRENT_TIMESTAMP() from `users` 
				where status = 1 and unsubscribe = 0
				order by RAND() limit 0, 2000;
			";
			
		Yii::app()->db->createCommand($sql)->execute();
		
		$subscribers = Notify::model('News')->findAll(array('condition' => 'is_send = 0 and message_text_id = 2222', 'limit' => $this->limit, 'offset' => $this->offset));
	    $i =0;
        foreach ($subscribers as $subscriber) {
			var_dump($i++);
			
            $user = Users::model()->findByPk($subscriber->user_id);
			if($user->status != 1){
				continue;
			}
			$criteria = new EMongoCriteria();
			$criteria->addCond('user_id', 'equals', $user->id);
			$Mnews     = Mongo_News::model()->find($criteria);
			
			if($Mnews){
				if(isset($Mnews->disable_notify['weekly_digest'])){
					$user->unsubscribe = 1;
					$user->save();
					continue;
				}
			}	
			
			$mail = new Mail();
			$mail->send($user, 'weeklyDigets', array('news_l3' => $news, 'title' => $title, 'news_other' => $news_other));
			
			$sql = "UPDATE `notify_news` SET `is_send`='1' WHERE (`id`='{$subscriber->id}')";
			Yii::app()->db->createCommand($sql)->execute();
		}
        
    }
	
	
	

}