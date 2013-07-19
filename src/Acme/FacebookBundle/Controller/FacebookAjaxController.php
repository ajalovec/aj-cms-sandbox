<?php

namespace Acme\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FacebookAjaxController extends Controller
{
	/**
	 * Akcja ajax zapisu do assortment
	 */
    public function saveFacebookUserToAssortmentAction($facebookUserId)
    {
    	
		return new Response('Facebook user id: '.$facebookUserId);
    }
	
	public function facebookUserIdAction()
	{
		return new Response('userid: '.$this->get('fos_facebook.api')->getUser());
	}
	
	public function facebookCheckGrantedAction()
	{
		$response = 'userID: '.$this->get('fos_facebook.api')->getUser().'<br>';
		if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){
			$response.= 'IS_AUTHENTICATED_FULLY <br>';
		}
		if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')){
			$response.= 'IS_AUTHENTICATED_ANONYMOUSLY <br>';
		}
		if($this->container->get('security.context')->isGranted('ROLE_ADMIN')){
			$response.= 'ROLE_ADMIN <br>';
		}

		return new Response($response);
	}
	
}
