<?php

class UserController extends Controller
{

    public $layout = 'user';
    public $title  = 'User Controller Param(change in action)';

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow readers only access to the view file
                'actions' => array('edit', 'deletenew', 'profile', 'uploadavatar'),
                'roles' => array('user', 'moderator', 'administrator')
            ),
            array('allow', // allow readers only access to the view file
                'actions' => array('index', 'createUserAvatar'),
                'users' => array('*')
            ),
            array('deny', // deny everybody else
                'users' => array('*')
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $id = Yii::app()->request->getParam('id', Yii::app()->user->id);
        if (!$id) {
            $this->redirect('/site/login');
        }

        if ($id != Yii::app()->user->id) {
            $this->forward('profile');
        }

        $model    = $this->loadModel($id);
        $criterea = new EMongoCriteria();
        $criterea->addCond('user_id', '==', Yii::app()->user->id);
        $news     = News::model()->find($criterea);
        $this->render('index', array('model' => $model, 'news'  => $news));
    }

    public function actionProfile()
    {
        $id    = Yii::app()->request->getParam('id', Yii::app()->user->id);
        $model = Users::model()->findByPk($id);
        $this->render('profile', array('model' => $model));
    }

    public function actionEdit()
    {
        if (Yii::app()->user->getIsGuest()) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $id    = Yii::app()->user->id;
        $model = $this->loadModel($id);
        $model->setScenario('general_update');

        if (Yii::app()->getRequest()->isAjaxRequest) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

//      TODO:: настроить ajax валидацию (сейчас нет ошибок в верстке (((( )
//        if (Yii::app()->getRequest()->isAjaxRequest && Yii::app()->getRequest()->getParam('ajax') == 'profileForm') {
//            $model->profile->setScenario('update');
//            echo CActiveForm::validate($model->profile);
//            Yii::app()->end();
//        }

        $redirect = false;
        $success  = true;

        if (isset($_POST['Users_Profile'])) {
            if (isset($_FILES['Users_Profile']['tmp_name']) && $_FILES['Users_Profile']['tmp_name']['avatar']) {
                $upRes                  = UserService::uploadAvatar($id, $_FILES['Users_Profile']['tmp_name']);
                $model->profile->avatar = $upRes['success'];
                if (!$model->profile->avatar) {
                    $model->profile->addError('avatar', $upRes['error']);
                }
            }
            if ($_POST['birthday']) {
                $birthday = implode('.', $_POST['birthday']);
                foreach ($_POST['birthday'] as $p) {
                    if (!$p) {
                        $birthday                   = $model->profile->birthday;
                    }
                }
            }
            $model->profile->birthday   = $birthday;
            $model->profile->setScenario('update');
            $model->profile->attributes = $_POST['Users_Profile'];
            if ($model->profile->validate() && $model->profile->save()) {
                $redirect = true;
            } else {
                $redirect = false;
                $success  = false;
            }
            $model->profile->birthday = Yii::app()->dateFormatter->formatDateTime($model->profile->birthday, 'medium', false);
        }

        if (isset($_POST['Users'])) {
            $model->setScenario('general_update');
            $model->attributes = $_POST['Users'];
            if ($model->validate() && $model->save() && $success) {
                $redirect = true;
            } else {
                $redirect = false;
            }
        }

        if ($redirect) {
            $this->redirect('/user/index');
        }

        $days = array(
            '0'    => Yii::t('Profile', 'день'),
        );
        $month = array(
            '0'   => Yii::t('Profile', 'месяц'),
            '01'  => Yii::t('Profile', 'январь'),
            '02'  => Yii::t('Profile', 'февраль'),
            '03'  => Yii::t('Profile', 'март'),
            '04'  => Yii::t('Profile', 'апрель'),
            '05'  => Yii::t('Profile', 'май'),
            '06'  => Yii::t('Profile', 'июнь'),
            '07'  => Yii::t('Profile', 'июль'),
            '08'  => Yii::t('Profile', 'август'),
            '09'  => Yii::t('Profile', 'сентябрь'),
            '10'  => Yii::t('Profile', 'октябрь'),
            '11'  => Yii::t('Profile', 'ноябрь'),
            '12'  => Yii::t('Profile', 'декабрь'),
        );
        $year = array(
            '0' => Yii::t('Profile', 'год')
        );
        for ($i = date('Y') - 100; $i < date('Y') - 14; $i++) {
            $year[$i] = $i;
        }
        for ($i = 1; $i < 32; $i++) {
            $days[$i] = $i;
        }

        $regions = Regions::model()->findAll('parent_id = 1 OR to_menu = 1 OR id = :city ORDER BY to_menu DESC', array(':city' => $model->profile->city_id));

        $this->render('profile/edit', array('model' => $model, 'regions' => $regions, 'month' => $month, 'year'  => $year, 'days'  => $days));
    }

    public function actionUploadAvatar(){
        Yii::import("application.extensions.EAjaxUpload.qqFileUploader");
        $model = $this->loadModel(Yii::app()->user->id);
        $path = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..';
        $folder = $path . Users::AVATAR_PATH . DIRECTORY_SEPARATOR . $model->id;
        $allowedExtensions = array("jpg","jpeg","png"); //array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 2 *1024 * 1024; // maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $uploader->setName('avatar');
        $oldUmask = umask ();
        umask ( 0 );
        $res = @mkdir ( $folder, 0777 );
        umask ( $oldUmask );
        $oldUmask = umask ();
        $result = $uploader->handleUpload($folder, true);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        $fileSize=filesize($folder.$result['filename']); //GETTING FILE SIZE
        if(isset($result['filename'])){
            $fileName = 'avatar.jpg';
        }
        $fileName = 'error';
        // TODO::резайзить файл сразу после загрузки
        UserService::uploadAvatar($model->id, $folder.$result['filename']);

        echo $return;// it's array
        Yii::app()->end();
    }

    public function loadModel($id)
    {
        $model = Users::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionCreateUserAvatar($id)
    {
        $user = Users::model()->findByPk($id);
        $file = Yii::app()->basePath . '/../images/users/' . $id . '/avatar.jpg';
        if (!file_exists($file) && $user) {
            $srcImage = UserService::uploadAvatarFromEmail($user->id, $user->email);
            $file     = Yii::app()->basePath . '/..' . $srcImage;
            $image    = Yii::app()->image->load($file);
            $image->render();
        } else {
            return false;
        }
    }

}
