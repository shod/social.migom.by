<?php
// yiic mail send --actions=10
class MailCommand extends ConsoleCommand {

    public function actionSend($user_id, $template) {
        $user = Users::model()->findByPk($user_id);
        if(!$user || !$user->email){
            $errors = array('message' => 'User not found or empty email');
            Yii::log($errors, CLogger::LEVEL_INFO);
			return true;
        }
        $mailer = Yii::app()->mailer;
//        if($mailer->Host){
//            $mailer->IsSMTP();
//        } else {
            $mailer->IsMail();
//        }

		$mailer->ClearAddresses();
		$mailer->AddAddress($user->email);
        $mailer->FromName = 'Social.Migom.By';
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = Yii::t('Mail', 'Social.Migom.By');
		$mailer->SingleTo = true;
		$mailer->Mailer = 'mail';
		$mailer->Sender = 'noreply@migom.by';
		$mailer->ClearCustomHeaders();
		//$mailer->AddCustomHeader('Return-path: <evgeniy.kazak@gmail.com>');
		$mailer->AddCustomHeader('Errors-To: <evgeniy.kazak@gmail.com>');
		$mailer->AddCustomHeader('Precedence: bulk');
		//$mailer->AddCustomHeader('From: migom.by<noreply@migom.by>');
		$mailer->AddCustomHeader('Reply-To: migom.by<noreply@migom.by>');
		//$mailer->AddCustomHeader('Return-Path: <noreply@migom.by>');

        $this->params['user'] = $user;
        $mailer->getView($template, $this->params);
        if(!$result = $mailer->Send()){
            $errors = array('message' => Yii::t('Command', 'Email not send (email = :email, template = :template)', array(':email' => $user->email, ':template' => $template)));
            Yii::log($errors, CLogger::LEVEL_INFO);
        }
		dd($mailer->ErrorInfo);
        return $result;
    }
}