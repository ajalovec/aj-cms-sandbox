<?php

namespace JMI\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name = "")
    {
        return $this->render('JMISiteBundle:Default:index.html.twig', array('name' => $name));
    }

    public function kontaktAction()
    {
        return $this->render('JMISiteBundle:Default:kontakt.html.twig', array('name' => $name));
    }

    public function demoAction()
    {
        return $this->render('JMISiteBundle:Default:demo.html.twig', array('name' => $name));
    }
}
