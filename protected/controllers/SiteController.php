<?php

class SiteController extends Controller {

	public $title;

    public function filters() {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow readers only access to the view file
                'actions' => array('error', 'static', 'login', 'test', 'logout', 'registration', 'info', 'remindPass', 'autocomplete', 'session'),
                'users' => array('*')
            ),
			array('allow', // allow readers only access to the view file
                'actions' => array('index', 'info'),
                'users' => array('administrator')
            ),
            array('deny', // deny everybody else
                'users' => array('*')
            ),
        );
    }

    public function actionIndex(){
        Widget::create('header');
    }
	
	public function actionInfo(){
        phpinfo();
    }
	
	public function actionStatic($url){
		
		$page = Pages::model()->find('url = :url', array(':url' => $url));
		if($page){
			$this->layout = 'user';
			$this->title = $page->title;
			$this->render('static', array('model'=> $page));
		}else{
			throw new CHttpException(404, 'Страница не найдена');
		}
	}

    public function actionError() {
        $this->layout = '';
        if ($error = Yii::app()->errorHandler->error) {
//			d($error);
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (!Yii::app()->user->getIsGuest()) {
			if(!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], Yii::app()->params['socialBaseUrl'].'/login') === 0){
				$this->redirect('/user/index');
			}
			$this->redirect($_SERVER['HTTP_REFERER']);	
        }

        if(isset($_SERVER['HTTP_REFERER']) && 
					!Yii::app()->request->isAjaxRequest && 
					!Yii::app()->request->isPostRequest && 
					!Yii::app()->request->getParam('reg_ask') &&
					!Yii::app()->request->getParam('haveALogin') &&
					!Yii::app()->request->getParam('new') &&
					Yii::app()->request->getBaseUrl(true).'/login' != $_SERVER['HTTP_REFERER'] && 
					Yii::app()->request->getBaseUrl(true).'/site/login' != $_SERVER['HTTP_REFERER']
				){
						Yii::app()->user->setReturnUrl($_SERVER['HTTP_REFERER']);
        }
        $this->layout = 'login';

        $service = Yii::app()->request->getQuery('service');
        if (isset($service)) {

            $authIdentity = Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl = Yii::app()->user->returnUrl;
//            $authIdentity->redirectUrl = $this->createUrl('/user/index');
            $authIdentity->cancelUrl = $this->createAbsoluteUrl('/site/login');

            if ($authIdentity->authenticate()) {
                $identity = new EAuthUserIdentity($authIdentity);

				if(!$identity->getAttribute('email')){
					$authIdentity->redirectUrl = Yii::app()->params['socialBaseUrl'] . '/profile/edit';
				}
				
                // successful authentication
                if ($identity->authenticate()) {
                    Yii::app()->user->login($identity);
                    if($identity->addNewSocial){
                        Users_Providers::addSocialToUser($identity, Yii::app()->user->getId());
                    }

                    // special redirect with closing popup window
                    $authIdentity->redirect();
                } elseif ($identity->errorCode == EAuthUserIdentity::ERROR_USER_NOT_REGISTERED) {
                    if(!Yii::app()->request->getParam('reg_ask')){
                        $this->layout = 'popup';
                        $this->render('login/new_user_ask', array('service' => $service, 'identity' => $identity));
                        Yii::app()->end();
                    } elseif(Yii::app()->request->getParam('user') == 'new'){
                        $reg = new Form_Registration();
                        $identity = $reg->registration($identity, $service);
                        if($identity instanceof Users){
							Yii::log('This email was taken: ""'.$identity->email, CLogger::LEVEL_INFO);
                            throw new CHttpException('400', Yii::t('Site', 'This email was taken'));
                        }
                        Yii::app()->user->login($identity);
                    } elseif(Yii::app()->request->getParam('user') == 'haveALogin'){
                        if(!isset($_POST['Form_Login'])){
                            $model = new Form_Login;
                            $this->layout = 'popup';
                            $this->render('login/popup', array('model' => $model));
                            Yii::app()->end();
                        }
                        $user = $this->_preLogin(false);
                        if($user->validate()){
                            Users_Providers::addSocialToUser($identity, Yii::app()->user->getId());
                        }
                    }
                    // special redirect with closing popup window
                    $authIdentity->redirect();
                } else {
                    // close popup window and redirect to cancelUrl
                    $authIdentity->cancel();
                }
            }

            $errors = array('message' => 'user was not login from ' . $service);
            Yii::log($errors, CLogger::LEVEL_INFO);

            // Something went wrong, redirect to login page
            $this->redirect(array('/site/login'));
        }

        $model = $this->_preLogin();
        $getErrors = (isset($_GET['mailError'])) ? $_GET['mailError'] : '';

        $regModel = new Form_Registration();
        $remindModel = new Form_Remind();
        $this->render('login', array('model' => $model, 'regModel' => $regModel, 'remindModel' => $remindModel, 'getErrors' => $getErrors));
    }

    protected function _preLogin($redirect = true){
        $model = new Form_Login;

        // if it is ajax validation request
        if (Yii::app()->getRequest()->isAjaxRequest && Yii::app()->getRequest()->getParam('ajax') == 'formLogin') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['Form_Login'])) {
            $model->attributes = $_POST['Form_Login'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login() && $redirect){
				Yii::log('User was loging. Email: '.Yii::app()->user->email.' UserID: '.Yii::app()->user->id, CLogger::LEVEL_INFO);
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        return $model;
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect((strpos($_SERVER['HTTP_REFERER'], Yii::app()->params['socialBaseUrl']) === false)?$_SERVER['HTTP_REFERER']:Yii::app()->params['migomBaseUrl']);
    }

    public function actionRegistration() {
        $model = new Form_Registration;

        // if it is ajax validation request
        if (Yii::app()->getRequest()->isAjaxRequest && Yii::app()->getRequest()->getParam('ajax') == 'formReg') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['Form_Registration'])) {
            $model->attributes = $_POST['Form_Registration'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()){
                $identity = $model->registration();
                Yii::app()->user->login($identity);
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        $this->redirect('/site/login');
    }

    public function actionRemindPass(){
        if(!Yii::app()->user->getIsGuest() || !Yii::app()->getRequest()->isAjaxRequest){
            throw new CHttpException(404, 'Страница не найдена');
        }

        $form = new Form_Remind();
        if (isset($_POST['Form_Remind']) && Yii::app()->getRequest()->isAjaxRequest) {
            $form->attributes = $_POST['Form_Remind'];
            if($form->validate() && isset($_POST['remind']) && $form->userModel->remindPassword()){
                echo json_encode(array('success' => true, 'message' => Yii::t('Site', 'Новый пароль будет выслан на почту')));
                Yii::app()->end();
            } else {
                echo CActiveForm::validate($form);
                Yii::app()->end();
            }
        }
    }
}