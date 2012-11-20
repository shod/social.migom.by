<?php

class News extends EMongoDocument {

    const NEWS_LINK = 'http://www.test3.migom.by?news_id=';

    public $user_id;
    public $entities;
    public $disable_entities;

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
          'class'=>'ext.YiiMongoDbSuite.extra.EEmbeddedArraysBehavior',
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

        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $parent->id;
            $entity->name = get_class($parent);
            $entity->created_at = $parent->created_at;
            $entity->template = 'news';
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

        // эти параметры следовало бы обновить в любом случае
        $name = array_pop(explode('_', get_class($parent)));
        $api = ERestDocument::model($name)->findByPK($comment->entity_id);
        $entity->link = self::getLink($name);
        $entity->entity_id = $comment->entity_id;
        $entity->filter = 'comment';
        $entity->title = $api->title;
        $entity->text = $parent->text;
        $entity->template = 'news';
        $entity->comment->count = $count;
        $entity->comment->attributes = $comment->attributes;
        $entity->comment->login = $comment->user->login;
        $entity->comment->id = $comment->id;

        $likesModel = Likes::model($entity->name)->findByPk($comment->id);
        if($likesModel){
            $entity->comment->likes->count = $likesModel->likes;
            $entity->comment->dislikes->count = $likesModel->dislikes;
        }

        $news->entities[] = $entity;
        return $news->save();
    }

    public static function getLink($name){
        if($name == 'News'){
            return self::NEWS_LINK;
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

        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $comment->id;
            $entity->name = get_class($comment);
            $entity->created_at = $comment->created_at;
            $entity->template = 'news';
        }

        $userLikes = self::_setLikesUsers($likes);
        $entity->likes->count = $likes->likes;
        $entity->likes->users = $userLikes['likesUsers'];
        $entity->dislikes->count = $likes->dislikes;
        $entity->dislikes->users = $userLikes['dislikesUsers'];

        // эти параметры следовало бы обновить в любом случае
        $entity->filter = 'comment';
        $entity->text = $comment->text;
        $entity->template = 'news';
        $news->entities[] = $entity;
        $news->save();
        self::_updateChildLikes($comment, $likes);
        return true;;
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