<?php
class MailCommandsTest extends CTestCase
{

	public $consoleCommands = array(
		'send',
	);

    public function testMailSend()
    {
		$user = Users::model()->findByPk(1);

		$mail = new Mail();
		$mail->send($user, 'registration', array('password' => 'testPass'));

	
        $command = new MailCommand('mail', 'Send');
		$command->run($this->consoleCommands);
    
		$log = Mail_Log::model()->find(array('condition' => 'user_id = 1', 'order' => 'created_at desc', 'limit' => 1));
		$this->assertEquals($log->template, 'registration');
		
		//TODO:: есть возможность проверять дошло ли письмо через api MailChimp
	}
}