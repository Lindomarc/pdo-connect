<?php
	
	namespace Lin;
	
	use Notification\Email\Email;
	use Rain\Tpl;
	
	
	class Mailer
	{
		public Email $newEmail;
		
		public array $data;
		
		public function __construct($toEmail,$toName,$subject,$tplName,$data)
		{
			$this->newEmail = new Email;
			
			// config
			$config = array(
				"base_url"      => null,
				"tpl_dir"       => VIEW.'email'.DIRECTORY_SEPARATOR,
				"cache_dir"     => VIEW_CACHE.'email',
				"debug"         => true
			);
			
			Tpl::configure( $config );
			
			$tpl = new Tpl;
			
			foreach ($data as $key => $value){
				$tpl->assign($key,$value);
			}
			
			$html = $tpl->draw($tplName, true);
			
			$this->data = [
				'subject' => $subject,
				'body' => $html,
				'replayEmail' => REPLAY_EMAIL,
				'replayName' => REPLAY_NAME,
				'addAddressEmail' => $toEmail,
				'addAddressName' => $toName,
				'fromEmail' => FROM_EMAIL,
				'fromName' => FROM_NAME
			];
			 
		}
		
		public function send()
		{
			return $this->newEmail->sendEmail($this->data);
		}
		
	}