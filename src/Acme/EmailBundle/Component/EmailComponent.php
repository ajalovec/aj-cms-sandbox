<?php

namespace Acme\EmailBundle\Component;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Swift_Mailer;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\EventDispatcher\EventDispatcher;
//use Symfony\Bundle\FrameworkBundle\Debug\TraceableEventDispatcher as EventDispatcher;
//use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher as EventDispatcher;
//use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;
//use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;
use Acme\EmailBundle\Event\EmailSendEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
/**
 * Klasa przechowujace emaile i pozwalajaca na zarzadzanie nimi
 */
class EmailComponent{
	
	protected $mailer;
	protected $em;
	protected $templating;
	protected $flash;
	protected $noErrorFlag;
	protected $numSent;
	protected $session;
	protected $flashBag;
	protected $dispatcher;
	//@doctrine.orm.entity_manager
	
	public function getEmailsByType($type)
	{
		$emails = $this->em->getRepository("AcmeEmailBundle:Email")->findByType($type);
		if (!$emails) {
			//throw $this->createNotFoundException('Nie znaleziono emaili');
			array_push($this->flash,'Nie znaleziono emaili');
		}else{
			return $emails;
		}
	}
	
	public function getOneEmailByType($type)
	{
		$email = $this->em->getRepository("AcmeEmailBundle:Email")->findOneByType($type);
		if (!$email) {
			//throw $this->createNotFoundException('Nie znaleziono emaili');
			array_push($this->flash,'Nie znaleziono emaili');
		}else{
			$email = array($email);
			return $email;
		}
	}
	public function processEmails($emailsEntities)
	{
		$recipients = array();
		foreach ($emailsEntities as $key => $emailEntity) {
			if(strlen($emailEntity->getName())>0&&strlen($emailEntity->getEmail())>0){
				$recipients[$emailEntity->getEmail()] = $emailEntity->getName();
			}else{
				if(strlen($emailEntity->getEmail())>0){
					$recipients[$key] = $emailEntity->getEmail();
				}else{
					array_push($this->flash,'Recipient powinien miec email');
					//throw new \Exception('Recipient powinien miec email');
				}
				
			}
		}
		return $recipients;
	}
	
	public function emailsCheck($emails)
	{
		$emailsToReturn = array();
		if(is_array($emails)){
			foreach ($emails as $key => $email) {
				if (is_int($key)) {
					if($this->swiftValidate($email)) array_push($emailsToReturn,$email);
				} else {
					if($this->swiftValidate($key)) array_push($emailsToReturn,$key);
				}
				
			}
		}
		return $emailsToReturn;
	}
	public function emailsStructChceck($emails,$processFlag,$type)
	{
		if($processFlag){
			try{
				foreach ($emails as $key => $email) {
					if(is_object($email)){
						if(!method_exists($email,'getEmail') && !method_exists($email,'getName')){
							$this->noErrorFlag = false;
							array_push($this->flash,'Obiekt/obiekty w '.$type.' powinny mieć metode getEmail i getName');
						}
					}else{
						$this->noErrorFlag = false;
						array_push($this->flash,'Podane dane '.$type.' maja zly format ');
					}

				}
			}catch(\Exception $e){
				$this->noErrorFlag = false;
				array_push($this->flash, $e);
			}
		}else{
			if(is_array($emails)){
				foreach ($emails as $key => $email) {
					if(!(is_string($email) || is_array($email))){
						$this->noErrorFlag = false;
						array_push($this->flash,"Email/emaile w tablicy $type maja nieprawidlowy format");
					}
				}
			}else{
				$this->noErrorFlag = false;
				array_push($this->flash,'Podane dane '.$type.' powinny miec format tablicy');
			}
		}
	}
	
	public function swiftValidate ($emailAdress)
	{
		if(\Swift_Validate::email($emailAdress)){
			return true;
		}else{
			array_push($this->flash,"Adres: $emailAdress jest niepoprawny.");
			return false;
		}
	}
	
	public function emailSend($senders,$recipients,$template,$templateObj,$title, $options = array())
	{
		$options = array_merge($this->getDefaultOptions(), $options);
		
		if(isset($options['processSenders'])){
			$processSenders = $options['processSenders'];
		}
		if(isset($options['processRecipients'])){
			$processRecipients = $options['processRecipients'];
		}
		
		if($templateObj === null){
			$this->noErrorFlag = false;
			//throw new \Exception('Template ogject powinien byc obiektem');
			array_push($this->flash,'Template variable powinien byc ustalony');
		}
		if($template === null){
			$this->noErrorFlag = false;
			//throw new \Exception('Template powinien nie byc pusty');
			array_push($this->flash,'Template powinien nie byc pusty');
		}
		if(!is_array($recipients)){
			$this->noErrorFlag = false;
			//throw new \Exception('Recipients powinny byc tablica');
			array_push($this->flash,'Recipients powinny byc tablica');
		}
		$this->emailsStructChceck($senders,$processSenders,'senders');
		$this->emailsStructChceck($recipients,$processRecipients,'recipients');
		if($this->noErrorFlag){
			$body = $this->templating->render($template, array('templateObj'=>$templateObj));

			if($processSenders){
				$senders = $this->processEmails($senders);
			}else{
				
			}
			if($processRecipients){
				$recipients = $this->processEmails($recipients);
			}
			
			$senders = $this->emailsCheck($senders);
			$recipients = $this->emailsCheck($recipients);
			
			if(count($senders)<1){
				$this->noErrorFlag = false;
				array_push($this->flash,'Podaj przynajmniej jednego poprawnego nadawce');
			}
			if(count($recipients)<1){
				$this->noErrorFlag = false;
				array_push($this->flash,'Podaj przynajmniej jednego poprawnego odbiorce');
			}
			if($this->noErrorFlag){
				$message = \Swift_Message::newInstance()
					->setSubject($title)
					->setFrom($senders)
					->setBody($body,'text/html')
				  ;
				$numSent = 0;
				foreach ($recipients as $address => $name)
				{
				  if (is_int($address)) {
				    $message->setTo($name);
				  } else {
				    $message->setTo(array($address => $name));
				  }
				  
					try {
						$mailerResult = $this->mailer->send($message, $failedRecipients);
					    $numSent += $mailerResult;
						if($mailerResult){
							if($options['dispatchEnable']){
								$this->dispatcher->dispatch('acme_email.send_email_event',  new EmailSendEvent($options['eventType'],$message->getFrom(),$message->getTo()));
							}
						}
						
					} catch (\Exception $e) {
					    array_push($this->flash,$e);
					}
				  
				}
				$this->numSent = $numSent;
			}
		}
	}
	/**
	 * Wypelnianie Flasha komunikatami nt mailingu
	 * 
	 * @param $clear bool - czy kasowac uprzednio flasz
	 * @param $typeSuccessName string - nazwa poprawnego komunikatu
	 * @param $typeErrorName string - nazwa niepoprawnego komunikatu
	 */
	public function fillFlashBag($clear = false,$typeSuccessName = 'success',$typeErrorName = 'error')
	{
		if($clear){
			$this->flashBag->clear();
		}
		if(is_array($this->flash) && count($this->flash)>0){
			foreach ($this->flash as $key => $singleFlashMessage) {
				$this->flashBag->add($typeErrorName, $singleFlashMessage);
			}
		}else{

		}
		$i = 0;
		$i = $this->getNumSent();
		if($i){
			$this->flashBag->add($typeSuccessName,"Wsyłano newsletter do $i użytkowników");
		}else{
			$this->flashBag->add($typeErrorName,"Nie wyslano zadnej wiadomosci");
		}
	}
	
	public function getFlash()
	{
		return $this->flash;
	}
	public function getNumSent()
	{
		return $this->numSent;
	}
	
	function getDefaultOptions()
	{
	    return array(
	        'processSenders'     => true,
	        'processRecipients'   => true,
	        'dispatchEnable'   => false,
	        'eventType'   => 'defaultEventType'
	    );
	}
	function __construct(Swift_Mailer $mailer, EntityManager $entityManager, EngineInterface $templating, Session $session ) 
	{	
		$this->mailer = $mailer;
		$this->em = $entityManager;	
		$this->templating = $templating;
		$this->flash = array();
		$this->session = $session;
		$this->flashBag = $session->getFlashBag();
		$this->noErrorFlag = true;
		$this->dispatcher =  new EventDispatcher();
	}
}


