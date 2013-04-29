<?php

/**
 * Work with user comment
 * @package api
 */
class CommentsController extends ERestController
{

    const CONTENT_COMMENTS = 'comments';
    const CONTENT_COMMENT = 'comment';

    private function _getModelName($entity)
    {
        return $entity;
    }

    /**
     * @ignore
     * @param type $id
     */
    public function actionGetComment($id)
    {

    }

    /**
     * @ignore
     * @param type $id
     * @param type $limit
     * @param type $start
     */
    public function actionGetUserList($id, $limit, $start = null)
    {

    }

    /**
     * @ignore
     * @param type $entity
     * @param type $id
     * @param type $limit
     * @param type $start
     */
    public function actionGetEntityList($entity, $id, $limit = null, $start = null)
    {
        $res = array();
        $criteria = new CDbCriteria;
        $criteria->condition = '`t`.`entity_id` = :entity_id and `t`.`status` = :status';
		$criteria->order = 't.created_at asc';
        $criteria->params = array(':entity_id' => $id,
            ':status' => Comments::STATUS_PUBLISHED);
        if ($limit) {
            $criteria->limit = $limit;
        }

        if ($start) {
            $criteria->offset = $start;
        }
        $rawData = Comments::model($entity)->with('user')->findAll($criteria);

        //TODO Как то не правельно related элименты так получать
        foreach ($rawData as $value) {

            $row = array();
            foreach ($value as $key => $attr) {
                $row[$key] = $attr;
            }
			if(isset($value->user)){
				foreach ($value->user as $key => $attr) {
					$row['user'][$key] = $attr;
				}
			}
//            foreach ($value->profile as $key => $attr) {
//                $row['users']['profile'][$key] = $attr;
//            }

            $res[] = $row;
        }

        $content = array(self::CONTENT_COMMENTS => $res, ERestComponent::CONTENT_COUNT => count($res));
        $this->render()->sendResponse($content);
    }

    /**
     * @ignore
     * @param string $entity
     * @param int $id
     * @param int $iser_id
     */
    public function actionGetEntityUserList($entity, $id)
    {
        $userId = (int) Yii::app()->request->getParam('user_id');
        $res = array();
        $criteria = new CDbCriteria;
        $criteria->condition = '`t`.`user_id` = :user_id and `t`.`entity_id` = :entity_id and `t`.`status` != :status';
        $criteria->params = array(':entity_id' => $id,
            ':status' => Comments::STATUS_DELETED,
            ':user_id' => $userId);
        $rawData = Comments::model($entity)->with('user')->findAll($criteria);

        //TODO Как то не правельно related элименты так получать
        foreach ($rawData as $value) {
            $row = array();
            foreach ($value as $key => $attr) {
                $row[$key] = $attr;
            }
            foreach ($value->user as $key => $attr) {
                $row['user'][$key] = $attr;
            }
            $res[] = $row;
        }

        $content = array(self::CONTENT_COMMENTS => $res, ERestComponent::CONTENT_COUNT => count($res));
        $this->render()->sendResponse($content);
    }
	
	public function actionGetEntityPopular($entity){

		$limit = (int) Yii::app()->request->getParam('limit', 5, 'int');
        $res = array();

		$sql = "select count(1) cnt, nc.* 
				from {$entity}_comments nc 
				where status != ".Comments::STATUS_DELETED."
				AND  `created_at` > UNIX_TIMESTAMP( ) -3600 *24 *30 *1
				group by entity_id order by cnt desc limit " . $limit;
		$command = Yii::app()->db->createCommand($sql)->queryAll();
		
        //TODO Как то не правельно related элименты так получать
        foreach ($command as $value) {
		    $res[] = $value['entity_id'];
        }

        $content = array('result' => $res);
        $this->render()->sendResponse($content);
	}

    /**
     * @ignore
     * @param string $entity
     * @param int $id
     * @param int $iser_id
     */
    public function actionGetEntityCount($entity)
    {
        $res = array();
		if(empty($_GET['id'])){
			throw new ERestException('empty parametr "id"');
		}
		$criteria = new CDbCriteria;
        $criteria->select = 'entity_id, count(*) as cnt';
        $criteria->addInCondition('entity_id', $_GET['id']);
        $criteria->condition = '`t`.`status` = :status and `t`.`entity_id` in ('. implode(',', $_GET['id']) .')';
		$criteria->params = array(':status' => Comments::STATUS_PUBLISHED);
        $criteria->group = '`t`.`entity_id`';
        $rawData = Comments::model($entity)->findAll($criteria);
        foreach ($rawData as $value) {

            $res[] = array('id' => $value->entity_id,
                'count' => $value->cnt);
        }

        $content = array(self::CONTENT_COMMENTS => $res);
        $this->render()->sendResponse($content);
    }

    public function actionPostEntity($entity)
    {
        $comment = Comments::model($entity, true);//new Comments_News();
		
        $comment->attributes = $_POST;
		$comment->parent_id = Yii::app()->request->getParam('parent_id', 0, 'int');
		if ($comment->save()) {
			$count = Comments::model($entity)->count('parent_id = :parent_id', array(':parent_id' => $comment->parent_id));
			if($comment->parent){
                News::pushComment($comment, $count);
            }else{
					switch($entity){
						case 'news':
							$apiModel = Api_News_Author::model();
							break;
						case 'article':
							$apiModel = Api_Article_Author::model();
							break;
						case 'adverts':
							$apiModel = Api_Adverts::model();
							break;
						default:
							$apiModel = Api_News_Author::model();
					}
					// find the new in view(view have not news without aothor)
					$new = $apiModel->find('id = :id', array(':id' => $comment->entity_id));
					if($new){
						$count = $comment::model()->count('entity_id = :eId AND parent_id = 0', array(':eId' => $comment->entity_id));
						News::pushCommentToAuthor($comment, $count, $new);
					}
				
			}
		    $content = array(self::CONTENT_COMMENT => $comment->attributes);
            $this->render()->sendResponse($content);
        } else {
			$error = $comment->getErrors();
		    throw new ERestException(var_export($error, 1));
        }
    }

    /**
     * @ignore
     * @param type $id
     * @param type $entity
     * @param type $recursive
     */
    public function actionDeliteComment($id, $entity, $recursive = true)
    {

    }

    /**
     * @ignore
     * @param type $id
     * @param type $entity
     * @param type $params
     */
    public function actionPutComment($id, $entity, $params = array())
    {

    }

}