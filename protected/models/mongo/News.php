<?php

class News extends EMongoDocument {

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
    public static function pushComment($comment){
        $parent = $comment->parent;
        list($news, $entity) = News::_push($parent->user_id, $parent->id, get_class($parent));
        
        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $parent->id;
            $entity->name = get_class($parent);
            $entity->created_at = $parent->created_at;
            $entity->template = 'news';
            
            $likesModel = Likes::model($entity->name)->findByPk($entity->id);
            if($likesModel){
                $entity->likes->count = $likesModel->likes;
                $entity->dislikes->count = $likesModel->dislikes;
            }
        }
        // эти параметры следовало бы обновить в любом случае
        $entity->filter = 'comment';
        $entity->text = $parent->text;
        $entity->template = 'news';
        $entity->comment->attributes = $comment->attributes;
        $entity->comment->login = $comment->user->login;
        
        $likesModel = Likes::model($entity->name)->findByPk($comment->id);
        if($likesModel){
            $entity->comment->likes->count = $likesModel->likes;
            $entity->comment->dislikes->count = $likesModel->dislikes;
        }
        
        
        $news->entities[] = $entity;
        return $news->save();
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
    public static function pushLike($parent, $like){
        list($news, $entity) = News::_push($parent->user_id, $parent->id, get_class($parent));
        
        if(!$entity){       // если новая запись на стене
            $entity = new News_Entity();
            $entity->id = $parent->id;
            $entity->name = get_class($parent);
            $entity->created_at = $parent->created_at;
            $entity->template = 'news';
            
            $userModel = Users::model()->findByPk($like['user']);
            $newUser = array($like['user'] => $userModel->login);
            d($userModel);
            if($like['weight'] > 0){
                $entity->likes->users[] = $newUser;
                $entity->likes->count = $like['likes'];
            }else{
                $entity->dislikes->users[] = $newUser;
                $entity->dislikes->count = $like['dislikes'];
            }
            
        }
        // эти параметры следовало бы обновить в любом случае
        $entity->filter = 'comment';
        $entity->text = $parent->text;
        $entity->template = 'news';
        d($entity);        
        $news->entities[] = $entity;
        return $news->save();
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