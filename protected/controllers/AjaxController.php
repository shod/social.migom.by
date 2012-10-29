<?php

class AjaxController extends Controller
{

//        public function filters()
//	{
//		return array(
//			'accessControl',
//		);
//	}
//
//	/**
//	 * Specifies the access control rules.
//	 * This method is used by the 'accessControl' filter.
//	 * @return array access control rules
//	 */
//	public function accessRules()
//	{
//		return array(
//                        array('allow', // allow readers only access to the view file
//                            'actions'=>array('edit', 'deletenew'),
//                            'roles' => array('user', 'moderator', 'administrator')
//                        ),
//                        array('allow', // allow readers only access to the view file
//                            'actions'=>array(),
//                            'users' => array('*')
//                        ),
//                        array('deny',   // deny everybody else
//                            'users' => array('*')
//                        ),
//		);
//	}

    public function init()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect('/user/index');
        }
    }

    public function actionUserNews()
    {
        $criteria = new EMongoCriteria();
        $criteria->addCond('user_id', 'equals', Yii::app()->user->id);
        $news     = News::model()->find($criteria);
        if (!$news) {
            $news          = new News();
            $news->user_id = Yii::app()->user->id;
        }
        if (isset($news->disable_entities[Yii::app()->request->getParam('filter')])) {
            unset($news->disable_entities[Yii::app()->request->getParam('filter')]);
        } elseif(Yii::app()->request->getParam('filter')) {
            $news->disable_entities[Yii::app()->request->getParam('filter')] = Yii::app()->request->getParam('filter');
        }
        $offset = Yii::app()->request->getParam('offset', 0);

        $news->save();
        $this->renderPartial('userNews', array('news' => $news, 'offset' => $offset));
    }

    public function actionDeleteNew($entity)
    {
        $aEntity = explode('_', $entity);
        array_pop($aEntity); // удаляем постфикс
        $id      = array_pop($aEntity);
        $entity  = implode('_', $aEntity);

        $criteria = new EMongoCriteria;
        $criteria->addCond('user_id', 'equals', Yii::app()->user->id);
        $news     = News::model()->find($criteria);
        foreach ($news->entities as $key => $en) {
            if ($en->name == $entity && $en->id == $id) {
                unset($news->entities[$key]);
            }
        }
        return $news->save();
    }

    public function actionShowComments($entity)
    {
        $aEntity  = explode('_', $entity);
        $template = array_pop($aEntity);
        $id       = array_pop($aEntity);
        $entity   = array_pop($aEntity);

        $comments = Comments::model($entity);
        $comments = $comments->findAllByAttributes(array('parent_id' => $id), array('order' => 'created_at ASC'));
        foreach ($comments as $comm) {
            $likes = Likes::model(get_class($comm))->findByPk($comm->id);
            if($likes){
                $comm->likes = $likes->likes;
                $comm->dislikes = $likes->dislikes;
            }
        }

        $this->renderPartial('wall/' . $template, array('comments' => $comments));
    }

    public function actionWallLike($entity)
    {
        $aEntity = explode('_', $entity);
        $weight  = array_pop($aEntity);
        $id      = array_pop($aEntity);
        $entity  = implode('_', $aEntity);

        switch ($weight) {
            case 'like':
                $weight = 1;
                break;
            case 'dislike':
                $weight = -1;
                break;
        }

        $comment = $entity::model()->findByPk($id);
        if (!$comment) {
            echo json_encode(array('success' => false));
            Yii::app()->end();
        }
        try {
            /* @var $likes Likes */
            if ($likes = Likes::model($entity)->findByPk($id)) {
                foreach ($likes->users as $user) {
                    if ($user->id == Yii::app()->user->id) {
                        echo json_encode(array('success' => false));
            Yii::app()->end();
                    }
                }
            } else {
                $model            = 'Likes_' . $entity;
                $likes            = new $model();
                $likes->entity_id = $id;
            }
        } catch (Exception $exc) {
            echo json_encode(array('success' => false));
            Yii::app()->end();
        }
        $user         = new Likes_Embidded_Users();
        $user->id     = Yii::app()->user->id;
        $user->login  = Yii::app()->user->name;
        $user->weight = $weight;

        $likes->users[] = $user;
        $likes->setWeightInc($weight);
        if ($likes->save()) {
            News::pushLike($comment, $likes);
            echo json_encode(array('success' => true));
            Yii::app()->end();
        }
        echo json_encode(array('success' => false));
        Yii::app()->end();
    }

}