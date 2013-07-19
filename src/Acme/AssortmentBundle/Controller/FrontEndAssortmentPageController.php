<?php

namespace Acme\AssortmentBundle\Controller;
use Acme\PageBundle\Component\PageComponent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;

class FrontEndAssortmentPageController extends Controller
{	
    public function assortmentPageViewAction($routeId = null)
    {
		$assortmentPageComponent = $this->container->get('acme_assortment.component.assortment_page_component');
		
		$assortmentPageComponent->getAssortmentPageInstance($routeId);
		$assortmentPage = $assortmentPageComponent->getAssortmentPage();
		
        return $this->render('AcmeAssortmentBundle::assortmentPageView.html.twig', array('title' => $assortmentPage->getTitle(), 'body' => $assortmentPage->getBody(), 'assortmentPageHasMedias' => $assortmentPage->getAssortmentPageHasMedias() ));
    }	
}
