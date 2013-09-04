<?php

namespace JMI\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getRepository("AcmeContentBundle:Content");
        $menu = $manager->getMenuTree();
        
        
        //$this->getDoctrine()->getRepository("")
        return $this->render('JMISiteBundle:Default:storitve.html.twig', array('name' => "Storitve"));
    }

    
    public function storitveAction()
    {
    	$manager = $this->getDoctrine()->getRepository("AcmeContentBundle:Content");
    	$menu = $manager->getMenuTree();
    	
    	
    	//$this->getDoctrine()->getRepository("")
        return $this->render('JMISiteBundle:Default:storitve.html.twig', array('name' => "Storitve"));
    }

}
