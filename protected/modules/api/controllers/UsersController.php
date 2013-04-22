<?php

/**
 * Work with user
 * @package api
 */
class UsersController extends ERestController
{
	
	public function actionGetInfo(){
		$id = Yii::app()->request->getParam('entity', '', 'int');
		$user = Users::model()->findByPk($id);
		if(!$user){
			$this->render()->sendResponse(
				array(
					ERestComponent::CONTENT_MESSAGE => Yii::t('Yama', 'Пользователь не найден'),
					ERestComponent::CONTENT_SUCCESS => false
				)
			);
			Yii::app()->end();
		}
		$res = $user->attributes;
		$res['fullname'] = $user->fullname;
		$this->render()->sendResponse(
			array(
				ERestComponent::CONTENT_MESSAGE => $res,
                'auth' => false,
                ERestComponent::CONTENT_SUCCESS => true
			)
		);
	}
	
	public function actionGetInfoByIds(){
		$id = Yii::app()->request->getParam('entity', '', 'list', array('list'));
		$ids = Yii::app()->request->getParam('ids');

		$users = Users::model()->findAll('id IN (' . implode(',', $ids) . ')');
		if(!count($users)){
			$this->render()->sendResponse(
				array(
					ERestComponent::CONTENT_MESSAGE => Yii::t('Yama', 'Пользователей не найдено'),
					ERestComponent::CONTENT_SUCCESS => false
				)
			);
			Yii::app()->end();
		}
		$res = array();
		foreach($users as $user){
			$res[$user->id] = $user->attributes;
			$res[$user->id]['fullname'] = $user->fullname;
		}
		
		$this->render()->sendResponse(
			array(
				ERestComponent::CONTENT_MESSAGE => $res,
                'auth' => false,
                ERestComponent::CONTENT_SUCCESS => true
			)
		);
	}
	
    /**
     * Check Login
     * @todo Реализовать проверку авторозованности
     * @param string $puid
     */
    public function actionGetAuth()
    {
        $puid = $_GET['puid'];
        $user = Yii::app()->cache->get('user_' . $puid);
        if ($user) {
            $content = array(ERestComponent::CONTENT_MESSAGE => Yii::t('Api', 'User is auth'),
                ERestComponent::CONTENT_ID => $user['id'],
                'auth' => true,
                ERestComponent::CONTENT_SUCCESS => true);
        } else {
            $content = array(ERestComponent::CONTENT_MESSAGE => Yii::t('Api', 'User is not auth'),
                'auth' => false,
                ERestComponent::CONTENT_SUCCESS => true);
        }
        $this->render()->sendResponse($content);
    }

    /**
     * Logout
     * @todo Реализовать логаут
     * @param string $key - puid
     */
    public function actionDeleteAuth($puid)
    {
        Yii::app()->cache->delete('user_' . $puid);
        $this->render()->sendResponse(array(ERestComponent::CONTENT_MESSAGE => Yii::t('Api', 'User logout'),
            ERestComponent::CONTENT_SUCCESS => true));
    }

    /**
     * Login
     * @todo Реализовать логин
     * @param string $login
     * @param string $paswd
     */
    public function actionPostAuth()
    {
        $login = $_POST['login'];
        $paswd = $_POST['paswd'];
        if ($login == 'login' && $paswd == 'paswd') {
            $puid = 'asd';
            Yii::app()->cache->set('user_' . $puid, array('id' => 100));

            $content = array(ERestComponent::CONTENT_MESSAGE => Yii::t('Api', 'User is auth'),
                ERestComponent::CONTENT_PUID => $puid);
        } else {
            $content = array(ERestComponent::CONTENT_MESSAGE => Yii::t('Api', 'User is not auth'));
        }
        $this->render()->sendResponse($content);
    }

}