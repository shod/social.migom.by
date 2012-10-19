<?php
// yiic mail send --actions=10
class MailCommand extends ConsoleCommand {

    public function actionSend($user_id, $template) {
        $user = Users::model()->findByPk($user_id);
        if(!$user || !$user->email){
//            throw new Exception(Yii::t('Console', 'User not found or empty email'), 404);
        }
        $mailer = Yii::app()->mailer;
//        if($mailer->Host){
//            $mailer->IsSMTP();
//        } else {
            $mailer->IsMail();
//        }
        $mailer->AddAddress($user->email);
        $mailer->FromName = 'Social.Migom.By';
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = Yii::t('Mail', 'Social.Migom.By');
        $mailer->getView($template, array('user'=>$user, 'params' => $this->params));
        return $mailer->Send();
    }
}