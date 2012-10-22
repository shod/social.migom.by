<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
//	public function actions()
//	{
//		return array(
//			// captcha action renders the CAPTCHA image displayed on the contact page
//			'captcha'=>array(
//				'class'=>'CCaptchaAction',
//				'backColor'=>0xFFFFFF,
//			),
//		);
//	}

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
                'actions' => array('index', 'error', 'login', 'test', 'logout', 'registration', 'info'),
                'users' => array('*')
            ),
            array('deny', // deny everybody else
                'users' => array('*')
            ),
        );
    }

    public function actionInfo() {
        phpinfo();
    }
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        
//        $queue = new Queue();
//        $queue->what = 'mail send';
//        $queue->user_id = 14;
//        $queue->priority = 100;
//        $queue->param = array('template' => 'registration', 'password' => 'd616as6df1');
//        if(!$queue->validate()){
//            d($queue->getErrors());
//        }
//        $queue->save();
//        die('<br/>__________________________________');
//        echo 'User Name: <b>' . Yii::app()->user->name . '</b>';
//        echo '<br/>------------------------<br/>';
//        foreach (get_class_methods(__CLASS__) as $methods) {
//            if (strpos($methods, 'action') === 0 && $methods !== 'actions') {
//                echo CHtml::link(substr($methods, 6), array('site/' . substr($methods, 6)));
//                echo '<br/>';
//            }
//        }
//            d(get_class_methods(__CLASS__));
    }

    public function actionTest() {
        
        $criteria = new CDbCriteria;
        $criteria->compare('soc_id', '105844357378365018543');
        $criteria->limit = 1;
        $provider = Users_Providers::model('g----------------------oogle_oauth');
        d($provider->find($criteria)->user->email);
        
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
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
            $this->redirect('/user/index');
        }
        if(isset($_SERVER['HTTP_REFERER']) && !Yii::app()->request->isAjaxRequest && !Yii::app()->request->isPostRequest && !Yii::app()->request->getQuery('service')){
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

                // successful authentication
                if ($identity->authenticate()) {
                    Yii::app()->user->login($identity, 3600*24*30);

                    // special redirect with closing popup window
                    $authIdentity->redirect();
                } elseif($identity->addNewSocial){
                    $user = $this->_preLogin(false);
                    Users_Providers::addSocialToUser($identity, Yii::app()->user->getId());
                    $authIdentity->redirect();
                }elseif ($identity->errorCode == EAuthUserIdentity::ERROR_USER_NOT_REGISTERED) {
                    if(!Yii::app()->request->getParam('reg_ask')){
                        $this->layout = 'popup';
                        $this->render('login/new_user_ask', array('service' => $service));
                        Yii::app()->end();
                    } elseif(Yii::app()->request->getParam('user') == 'new'){
                        $reg = new Form_Registration();
                        $identity = $reg->registration($identity, $service);
                        if($identity instanceof Users){
                            throw new CHttpException('400', Yii::t('Site', 'This email was taken'));
                        }
                        Yii::app()->user->login($identity, 3600*24*30);
                    } elseif(Yii::app()->request->getParam('user') == 'haveALogin'){
                        if(!isset($_POST['Form_Login'])){
                            $this->layout = 'popup';
                            $this->render('login/popup');
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

            // Something went wrong, redirect to login page
            $this->redirect(array('/site/login'));
        }

        $model = $this->_preLogin();
        $getErrors = (isset($_GET['mailError'])) ? $_GET['mailError'] : '';

        $regModel = new Form_Registration();
        $this->render('login', array('model' => $model, 'regModel' => $regModel, 'getErrors' => $getErrors));
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
        $this->redirect(Yii::app()->user->returnUrl);
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
                Yii::app()->user->login($identity, 3600*24*30);
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        $this->redirect('/site/login');
    }
    
    public function actionSocialReg(){
        
    }
}