<?php

class News extends EMongoDocument {

    public $user_id;
    public $entities;
    public $disable_entities;
	public $disable_notify;

    public function embeddedDocuments() {  // встроенные, суб массивы!
        return array(
            // property name => embedded document class name
            //  'entities' => 'NewsEntity'
        );
    }

    public function behaviors()
    {
      return array(
        array(
          'class'=>'core.extensions.YiiMongoDbSuite.extra.EEmbeddedArraysBehavior',
          'arrayPropertyName'=>'entities', // name of property
          'arrayDocClassName'=>'News_Entity' // class name of documents in array
        ),
      );
    }

    /**
     * This method have to be defined in every Model
     * @return string MongoDB collection name, witch will be used to store documents of this model
     */
    public function getCollectionName() {
        return 'news';
    }

    /**
     * We can define rules for fields, just like in normal CModel/CActiveRecord classes
     * @return array
     */
    public function rules() {
        return array(
            array('user_id', 'required'),
//            array('user_id', 'integerOnly' => true),
        );
    }

    /**
     * This method have to be defined in every model, like with normal CActiveRecord
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

    protected static function _push($user_id, $entity_id, $name){
        $criteria = new EMongoCriteria();
        $criteria->addCond('user_id', '==', $user_id);

        $news = News::model()->find($criteria);
        if(!$news){
            $news = new News();
            $news->user_id = $user_id;
        }

        $entity = false;
        if($news->entities){
            foreach ($news->entities as $key => $en){
                if($en->id == $entity_id && $en->name == $name){
                    $entity = $news->entities[$key];
                    unset($news->entities[$key]);
                }
            }
        }

        return array($news, $entity);
    }

    protected static function _setLikesUsers(Likes $likes){
        $total = $likes->likes + $likes->dislikes;
        $plus = $likes->likes * 10 / $total;
        $plus = ceil($plus);
        if($plus == 0){
           $plus = 1;
        } elseif($plus == 10){
            $plus = 9;
        }
        $userPlus = array();
        $userMinus = array();
        foreach($likes->users as $user){
            if($user['weight'] > 0 && count($userPlus) < $plus){
                $userPlus[] = $user->attributes;
            } elseif($user['weight'] < 0 && count($userMinus) < (10 - $plus)){
                $userMinus[] = $user->attributes;
            }
            if((count($userMinus) + count($userPlus)) == 10 ){
                break;
            }
        }

        return array(
                'likesUsers' => $userPlus,
                'dislikesUsers' => $userMinus,
            );
    }

    protected static function _updateChildLikes($comment, $likes){
        if(!$comment->parent_id){
            return;
        }
        $criteria = new EMongoCriteria();
        $criteria->user_id('==', $comment->parent->user_id);

        $news = News::model()->find($criteria);

        if(!$news){
            return;
        }

        foreach($news->entities as &$entity){
            if($entity->name == get_class($comment)){
                if($entity->comment->id == $comment->id){
                    $entity->comment->likes->count = $likes->likes;
                    $entity->comment->dislikes->count = $likes->dislikes;
                }
            }
        }
        $news->save();
    }

	private static function _getEntityInfo($name, $id){
		try {
			$model = ERestDocument::model($name);
			switch ($name) {
				case 'Product':
					$apiRes = $model->getInfo('attr', array('id' => array($id), 'list' => array('title', 'url', 'image'), 'image_size' => 'small'));
					if(!$apiRes){
						throw new exeption('ERROR CONNECTION to: ' . $name);
					}
					$api = $apiRes->$id;
					break;
				default:
					$api = $model->findByPK($id);
			}
        } catch (Exception $exc) {
			Yii::log('ERROR CONNECTION -  ' . $exc->getMessage(), 'api');
            $api = new stdClass();
            $api->title = $name;
        }
		return $api;
	
	}
	
    /**
     * Смотри News_Entity
     * @param type $user_id     - Юзер чей коммент
     * @param type $entity_id   - Id объекта сущности, к примеру новость 29
     * @param type $name        - имя сущности, к примеру news
     * @param type $text        - текст своего комментария
     * @param type $create_at   - дата создания коммента
     * @param type $comment     - комментарий на комментарий пользователя
     * @param type $likes       - массив лайков
     * @param type $dislikes    - массив дислайков
     * @return type
     */
    public static function pushComment($comment, $count){
        $parent = $comment->parent;
		
        list($news, $entity) = News::_push($parent->user_id, $parent->id, get_class($parent));
		
		$name = array_pop(explode('_', get_class($parent)));
        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $parent->id;
            $entity->name = get_class($parent);
            $entity->created_at = $parent->created_at;
            $entity->template = self::getTemplate($name);
        }
		
//        $criteria = new EMongoCriteria();
//        $criteria->entity_id = "1";

        $likesModel = Likes::model($entity->name)->findByPk($parent->id);
        if($likesModel){
            $userLikes = self::_setLikesUsers($likesModel);
            $entity->likes->count = $likesModel->likes;
            $entity->likes->users = $userLikes['likesUsers'];
            $entity->dislikes->count = $likesModel->dislikes;
            $entity->dislikes->users = $userLikes['dislikesUsers'];
        }


		$api = self::_getEntityInfo($name, $comment->entity_id);
        // эти параметры следовало бы обновить в любом случае
        
        $entity->link = self::getLink($name);
        $entity->entity_id = $comment->entity_id;
        $entity->filter = 'comment';
        $entity->title = ($api?$api->title:'');
        $entity->text = $parent->text;
        $entity->template = 'news';
        $entity->comment->count = $count;
        $entity->comment->user_id = $comment->user_id;
		$entity->comment->text = $comment->text;
		$entity->comment->created_at = $comment->created_at;
        $entity->comment->login = $comment->user->login;
        $entity->comment->id = $comment->id;

        $likesModel = Likes::model($entity->name)->findByPk($comment->id);
        if($likesModel){
            $entity->comment->likes->count = $likesModel->likes;
            $entity->comment->dislikes->count = $likesModel->dislikes;
        }
		
		// письмо "На Ваш комментарий ответили"
		if(!Yii::app()->cache->get('online_user_' . $parent->user_id) && !isset($news->disable_notify['comments_activity'])){
			$mail = new Mail;
			$mail->sendCommentsNotification($comment, 'News', $entity->title);
		}
		Yii::app()->notify->sendUserNotify($parent->user_id, 'wall');
		//UserService::addNotification($parent->user_id);

        $news->entities[] = $entity;
        return $news->save();
    }
	
	public static function pushCommentToAuthor($comment, $count, $new){
        $name = array_pop(explode('_', get_class($comment)));
		list($news, $entity) = News::_push($new->user_id, $comment->entity_id, $name);
		
		if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $new->id;
            $entity->name = $name;
            $entity->created_at = $new->start_date;
            $entity->template = 'newsAuthor';
        }

        // эти параметры следовало бы обновить в любом случае

        $entity->link = self::getLink($name);
        $entity->entity_id = $comment->entity_id;
        $entity->filter = 'comment';
        $entity->title = $new->title;
        $entity->text = $new->anounce_text;
        $entity->template = 'newsAuthor';
        $entity->comment->count = $count;
        $entity->comment->user_id = $comment->user_id;
		$entity->comment->text = $comment->text;
		$entity->comment->created_at = $comment->created_at;
        $entity->comment->login = $comment->user->login;
        $entity->comment->id = $comment->id;

        $likesModel = Likes::model(get_class($comment))->findByPk($comment->id);
        if($likesModel){
            $entity->comment->likes->count = $likesModel->likes;
            $entity->comment->dislikes->count = $likesModel->dislikes;
        }
		
		// письмо "На Ваш комментарий ответили"
		if(!Yii::app()->cache->get('online_user_' . $parent->user_id) && !isset($news->disable_notify['comments_activity'])){
			$mail = new Mail;
			$mail->sendCommentsAuthorNotification($comment, 'News', $entity->title, $new->user_id);
		}
		Yii::app()->notify->sendUserNotify($new->user_id, 'wall');
		//UserService::addNotification($parent->user_id);
		
        $news->entities[] = $entity;
        return $news->save();
    }

    public static function pushProduct($user, $product, $productInfo, $template, $name){
        $name = 'price_down';
		$template = 'priceDown';
        list($news, $entity) = News::_push($user->id, $product['product_id'], $name);

        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $product['product_id'];
            $entity->name = $name;
            $entity->created_at = time();
        }

        // эти параметры следовало бы обновить в любом случае
        $entity->link = self::getLink($name);
        $entity->entity_id = $product['product_id'];
        $entity->filter = $name;
        $entity->title = $productInfo->title;
        $entity->image = $productInfo->image;
        $entity->cost = $product['cost'];
        $entity->template = $template;

        $news->entities[] = $entity;
        return $news->save();
    }
	
	public static function pushInSale($user, $product, $productInfo){
        $name = 'in_sale';
		$template = 'inSale';
        list($news, $entity) = News::_push($user->id, $product['product_id'], $name);

        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $product['product_id'];
            $entity->name = $name;
            $entity->created_at = time();
        }

        // эти параметры следовало бы обновить в любом случае
        $entity->link = self::getLink($name);
        $entity->entity_id = $product['product_id'];
        $entity->filter = $name;
        $entity->title = $productInfo->title;
        $entity->image = $productInfo->image;
        $entity->cost = $product['cost'];
        $entity->template = $template;

        $news->entities[] = $entity;
        return $news->save();
    }
	
	public static function pushHellow($user){
        $name = 'migom_hellow';
        list($news, $entity) = News::_push($user->id, 1, $name);

        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = 1;
            $entity->name = $name;
            $entity->created_at = time();
        }

        // эти параметры следовало бы обновить в любом случае
        $entity->link = Yii::app()->params['migomBaseUrl'];
        $entity->entity_id = 1;
        $entity->filter = $name;
        $entity->title = Yii::t('Social', 'Добро пожаловать на Migom.by!');
        //$entity->image = $productInfo->image;
        //$entity->cost = $product['cost'];
//        $entity->text = '';
        $entity->template = 'migomHello';

        $news->entities[] = $entity;
        return $news->save();
    }

    public static function getLink($name){
        switch ($name) {
            case 'News':
                return Yii::app()->params['migomBaseUrl'].'?news_id=';
                break;
				
			case 'Article':
                return Yii::app()->params['migomBaseUrl'].'?article_id=';
                break;
			
			case 'Product':
                return Yii::app()->params['migomBaseUrl'].'/';
                break;
				
            case 'price_down':
                return Yii::app()->params['migomBaseUrl'];
                break;

            default:
                break;
        }
    }
	
	public static function getTemplate($name){
        switch ($name) {
			case 'Product':
                return 'product';
                break;
            case 'News':
			case 'Article':
            case 'price_down':
                return 'news';
                break;

            default:
                break;
        }
    }

    /**
     * Смотри News_Entity
     * @param type $user_id     - Юзер на чью сущночть поставили лайк
     * @param type $entity_id   - Id объекта сущности, к примеру новость 29
     * @param type $name        - имя сущности, к примеру news
     * @param type $text        - текст своего комментария
     * @param type $create_at   - дата создания лайка
     * @param type $weight      - вес лайка
     * @param type $likes       - массив лайков
     * @param type $dislikes    - массив дислайков
     * @return type
     */
    public static function pushLike($comment, $likes){
        list($news, $entity) = News::_push($comment->user_id, $comment->id, get_class($comment));
		
		$name = array_pop(explode('_', get_class($comment)));
        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $comment->id;
            $entity->name = get_class($comment);
            $entity->created_at = $comment->created_at;
            $entity->template = self::getTemplate($name);
        }

        $userLikes = self::_setLikesUsers($likes);
        $entity->likes->count = $likes->likes;
        $entity->likes->users = $userLikes['likesUsers'];
        $entity->dislikes->count = $likes->dislikes;
        $entity->dislikes->users = $userLikes['dislikesUsers'];
		
		Yii::log('name ' . $name, 'api');
        
        $api = self::_getEntityInfo($name, $comment->entity_id);
		
        // эти параметры следовало бы обновить в любом случае
		$entity->link = self::getLink($name);
		$entity->entity_id = $comment->entity_id;
        $entity->filter = 'comment';
        $entity->text = $comment->text;
		$entity->title = ($api?$api->title:'');
        $entity->template = self::getTemplate($name);
        $news->entities[] = $entity;
        $news->save();
		Yii::log('save', 'api');
        self::_updateChildLikes($comment, $likes);
		
		$criteria = new EMongoCriteria();
		$criteria->addCond('user_id', 'equals', $comment->user_id);
		$news     = News::model()->find($criteria);
		
		if(!Yii::app()->cache->get('online_user_' . $comment->user_id) && !isset($news->disable_notify['all_activity'])){
			Mail::addActivityNotification($comment->user_id);
		} else {
			Mail::deleteActivityNotification($comment->user_id);
		}
		Yii::app()->notify->sendUserNotify($comment->user_id, 'wall');
		//UserService::addNotification($comment->user_id);
        return true;
    }

//    public static function pushLikeDislike($user_id, $entity_id, $name){
//        list($news, $entity) = News::push($user_id, $entity_id, $name);
//    }

    public function afterFind() {
        if(!$this->disable_entities){
            $this->disable_entities = array();
        }
        return parent::afterFind();
    }
}
