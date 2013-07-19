<?php

namespace Acme\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FacebookController extends Controller
{
	/**
	 * Wyświetlenie strony z guzikiem facebook zabezpieczonej w security (test)
	 */
    public function fbSecuredAction()
    {
    	
		var_dump($this->get('fos_facebook.api')->getUser());
		
        return $this->render('AcmeFacebookBundle::facebook.html.twig', array('name' => 'START'));
    }
	
	
	/**
	 * Wyświetlenie strony z guzikiem facebook zabezpieczonej w security (test)
	 */
    public function fbNonSecuredAction()
    {
    	
		//var_dump($this->get('fos_facebook.api')->getUser());
		
		$acmeFacebookComponent = $this->get('acme_facebook.component.acme_facebook_component');
		$acmeFacebookComponent->saveFacebookUserIntoDB();
		
        return $this->render('AcmeFacebookBundle::fos_facebook.html.twig');
    }
	
	/**
	 * Wyświetlenie strony z guzikiem facebook
	 */
    public function facebookAction()
    {
    	
		var_dump($this->get('fos_facebook.api')->getUser());
		
        return $this->render('AcmeFacebookBundle::facebook.html.twig', array('name' => 'START'));
    }
	
	public function facebookUsersListAction()
	{
		
		$facebookUserPagePaginator = $this->get('acme_facebook.component.facebook_user_page_paginator');
		$pagination = $facebookUserPagePaginator->getPagination();
		
        return $this->render('AcmeFacebookBundle::facebookUsersListView.html.twig', array('title' => 'Facebookowicze', 'body' => '', 'modules' => array(), 'categories' =>null ,'pagination' => $pagination));

	}
	
    public function testAction()
    {
    	
		return new Response('Hello world!');
    }
	
}
