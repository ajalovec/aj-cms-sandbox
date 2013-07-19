<?php

namespace Acme\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Acme\PageBundle\Entity\Route as CustomRoute;
use Acme\PageBundle\Entity\Page;
use Doctrine\Common\Util\Debug as Debug;
use Doctrine\Common\Collections\Criteria;

class FrontEndPageController extends Controller
{
    public function startViewAction()
    {
		$page = $this->container->get('acme_page.page.page_component');
		$frontRouteId = $page->getFrontRouteId();
		$page->getPageInstance($frontRouteId);
		$forward = $page->getForward();
		if($forward){
		    $response = $this->forward($forward, array(
		        'page'  => $page
		    ));
			return $response;
		}
        return $this->render('AcmePageBundle::frontPageView.html.twig', array('title' => $page->getTitle(), 'body' => $page->getBody(), 'modules' => $page->getPageModules()));
    }
    public function pageViewAction($routeId = null)
    {
		$page = $this->container->get('acme_page.page.page_component');
		$page->getPageInstance($routeId);
		
		$forward = $page->getForward();
		$request = $this->get('request');
		if($forward){
		    $response = $this->forward($forward, array(
		        'page'  => $page
		    ));
			return $response;
		}
        return $this->render('AcmePageBundle::pageView.html.twig', array('title' => $page->getTitle(), 'body' => $page->getBody(), 'modules' => $page->getPageModules()));
    }	
	
    public function pageAction($pagename)
    {
		$myService = $this->get('acme_page.repository.router');
		
		echo "<pre>";
		print_r($myService->findManyByUrl("bleh"));
		echo "</pre>";
        return $this->render('AcmePageBundle:Default:index.html.twig', array('name' => $pagename));
    }	

}
