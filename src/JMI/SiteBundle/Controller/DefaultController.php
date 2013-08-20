<?php

namespace JMI\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    
    public function oPodjetjuAction($name = "")
    {
        return $this->render('JMISiteBundle:Default:oPodjetju.html.twig', array('name' => $name));
    }

    public function storitveAction()
    {
        return $this->render('JMISiteBundle:Default:storitve.html.twig', array('name' => "Storitve"));
    }


    
}
