<?php
// yiic mail send --actions=10
class MailCommand extends ConsoleCommand {

    public function actionSend($user_id, $template) {
	
        $user = Users::model()->findByPk($user_id);
        if(!$user || !$user->email){
            $errors = array('message' => 'User not found or empty email');
            Yii::log($errors, CLogger::LEVEL_ERROR, 'console');
			return true;
        }
        $mailer = Yii::app()->mailer;
		
		
		$mailer->IsSMTP();
		$mailer->ClearAddresses();
		$mailer->AddAddress($user->email);
        $mailer->FromName = 'Migom.By';
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = Yii::t('Mail', 'Migom.By');
		$mailer->SingleTo = true;
		$mailer->Mailer = 'smtp';
		$mailer->Hostname = 'migom.by';
		$mailer->Sender = 'no_reply@migom.by';
		/*$mailer->ClearCustomHeaders();
		$mailer->AddCustomHeader('Errors-To: <no_reply@migom.by>');
		$mailer->AddCustomHeader('Precedence: bulk');
		$mailer->AddCustomHeader('Reply-To: <no_reply@migom.by>');
		$mailer->AddCustomHeader('Return-Path: <no_reply@migom.by>');*/

        $this->params['user'] = $user;
		$this->params['mailer'] = $mailer;
		try {
            $mailer->getView($template, $this->params);
			$result = $mailer->Send();
			if($result){
				$mailLog = new Mail_log();
				$mailLog->user_id = $user->id;
				$mailLog->template = $template;
				$mailLog->save();
			}			
        } catch (CException $exc) {
			$errors = array('message' => Yii::t('Command', 'Email error: {ex}', array('{ex}' => $exc->getTraceAsString())));
            Yii::log($errors['message'], CLogger::LEVEL_ERROR, 'email_error');
			$result = false;
        }
        
        if(!$result){
            $errors = array('message' => Yii::t('Command', 'Email not send (email = :email, template = :template)', array(':email' => $user->email, ':template' => $template)));
            Yii::log($errors['message'], CLogger::LEVEL_ERROR, 'email_not_send');
        }
        return $result;
    }
	
	public function actionActivityNotification($user_id, $template){
		$user = Users::model()->findByPk($user_id);
        if(!$user || !$user->email){
            $errors = array('message' => 'User not found or empty email');
            Yii::log($errors, CLogger::LEVEL_ERROR, 'console');
			return true;
        }
        $mailer = Yii::app()->mailer;

		$mailer->IsSMTP();
		$mailer->ClearAddresses();
		$mailer->AddAddress($user->email);
        $mailer->FromName = 'Migom.By';
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = Yii::t('Mail', 'Migom.By');
		$mailer->SingleTo = true;
		$mailer->Mailer = 'smtp';
		$mailer->Hostname = 'migom.by';
		$mailer->Sender = 'noreply@migom.by';
		/*$mailer->ClearCustomHeaders();
		$mailer->AddCustomHeader('Errors-To: <noreply@migom.by>');
		$mailer->AddCustomHeader('Precedence: bulk');
		$mailer->AddCustomHeader('Reply-To: <noreply@migom.by>');
		$mailer->AddCustomHeader('Return-Path: <noreply@migom.by>');*/
        
		$this->params['user'] = $user;
		$this->params['mailer'] = $mailer;
        $mailer->getView($template, $this->params);
        if(!$result = $mailer->Send()){
            $errors = array('message' => Yii::t('Command', 'Email not send (email = :email, template = :template)', array(':email' => $user->email, ':template' => $template)));
            Yii::log($errors, CLogger::LEVEL_ERROR, 'console');
        } else {
			$mailLog = new Mail_log();
			$mailLog->user_id = $user->id;
			$mailLog->template = $template;
			$mailLog->save();
		}
        return $result;
	}
}