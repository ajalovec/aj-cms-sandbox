<?php
namespace Acme\FacebookBundle\Component;
use Acme\FacebookBundle\Entity\FacebookUser;
use Doctrine\ORM\EntityManager;
use FOS\FacebookBundle\Facebook\FacebookSessionPersistence;
use Doctrine\Common\Util\Debug;
/**
 * Class with provides some features to use with assortment bundle
 */
class AcmeFacebookComponent {
	
	protected $fos_facebook;
	protected $em;
	
	public function saveFacebookUserIntoDB()
	{
		$userId = $this->fos_facebook->getUser();
		$facebookUserRepo = $this->em->getRepository('AcmeFacebookBundle:FacebookUser');
		
		/*echo "<pre>";
		echo $userId;
		echo "</pre>";

		echo "<pre>";
		Debug::dump($facebookUserRepo->findByFacebookUserId($userId));
		echo "</pre>";
		
		
		if($facebookUserRepo->findByFacebookUserId($userId)){
			echo "#1 tak";
		}
		
		die();  */

		if(!$facebookUserRepo->findByFacebookUserId($userId)){
			if($userId){
				$userInfo = $this->fos_facebook->api('/'.$userId); 
				
				$facebookUser = new FacebookUser();
				
				$facebookUser->setFacebookUserId($userId);
				if(isset($userInfo['name'])) $facebookUser->setName($userInfo['name']);
				if(isset($userInfo['first_name'])) $facebookUser->setFirstName($userInfo['first_name']);
				if(isset($userInfo['last_name'])) $facebookUser->setLastName($userInfo['last_name']);
				if(isset($userInfo['birthday'])) $facebookUser->setBirthday($userInfo['birthday']);
				if(isset($userInfo['gender'])) $facebookUser->setGender($userInfo['gender']);
				if(isset($userInfo['timezone'])) $facebookUser->setTimezone($userInfo['timezone']);
				if(isset($userInfo['locale'])) $facebookUser->setLocale($userInfo['locale']);
				if(isset($userInfo['verified'])) $facebookUser->setVerified($userInfo['verified']);
				if(isset($userInfo['updated_time'])) $facebookUser->setUpdatedTime($userInfo['updated_time']);
	
				$this->em->persist($facebookUser);
				$this->em->flush(); 
			}
		}
	}
	
	function __construct(EntityManager $entityManager, FacebookSessionPersistence $fos_facebook) {
		
		$this->em = $entityManager;
		$this->fos_facebook = $fos_facebook;
	}
}