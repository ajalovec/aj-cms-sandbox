<?php

namespace Acme\AssortmentBundle\Controller;
use Acme\PageBundle\Component\PageComponent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;

class FrontEndAssortmentCategoryController extends Controller
{
    public function assortmentCategoryStartAction(PageComponent $page)
    {
		$categoriesRepo = $this->getDoctrine()->getRepository('AcmeAssortmentBundle:AssortmentCategory');
		$categories = $categoriesRepo->findAll();
		
	    $em    = $this->get('doctrine.orm.entity_manager');
	    $dql   = "SELECT ac FROM AcmeAssortmentBundle:AssortmentCategory ac";
	    $query = $em->createQuery($dql);
		
		$assortmentPagePaginator = $this->get('acme_assortment.component.assortment_page_paginator');
		$pagination = $assortmentPagePaginator->getPagination();
			
        return $this->render('AcmeAssortmentBundle::assortmentCategoryView.html.twig', array('title' => $page->getTitle(), 'body' => $page->getBody(), 'modules' => $page->getPageModules(), 'categories' =>$categories,'pagination' => $pagination));
    }	
	
    public function assortmentCategoryViewAction($routeId = null)
    {
		$assortmentCategoryComponent = $this->container->get('acme_assortment.component.assortment_category_component');
		$assortmentCategoryComponent->getAssortmentCategoryInstance($routeId);
		$assortmentCategory = $assortmentCategoryComponent->getAssortmentCategory();
		
		$categoriesRepo = $this->getDoctrine()->getRepository('AcmeAssortmentBundle:AssortmentCategory');
		$categories = $categoriesRepo->findAll();
		
		$assortmentPagePaginator = $this->get('acme_assortment.component.assortment_page_paginator');
		$pagination = $assortmentPagePaginator->getPagination($assortmentCategory);
		
        return $this->render('AcmeAssortmentBundle::assortmentCategoryView.html.twig', array('title' => $assortmentCategoryComponent->getTitle(), 'body' => '', 'modules' => array(), 'categories' =>$categories,'pagination' => $pagination));
    }	
}
