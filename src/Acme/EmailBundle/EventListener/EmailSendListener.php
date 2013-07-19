<?php
//Acme/MainLogicBundle/EventListener/EmailSendListener.php
namespace Acme\EmailBundle\EventListener;
use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManager;

class EmailSendListener
{
	protected $em;
	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}
	public function emailSendAction(Event $event)
	{
		$type = $event->getType();
		if($type == 'updateCandidateStatus'){
			$to = $event->getOneTo();
			//$from = $event->getOneFrom();
			if($to){
				$candidateRepo = $this->em->getRepository('AcmeMainLogicBundle:UserCandidate');
				$candidate = $candidateRepo->findOneByEmail($to);
				if($candidate){
					$candidate->setStatus(1);
					$this->em->persist($candidate);
					$this->em->flush();
				}
				
			}
			
		}
	}
}