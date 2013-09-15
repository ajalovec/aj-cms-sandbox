<?php

namespace Acme\ServicesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('AcmeServicesBundle:Default:index.html.twig', array(
        	'name' => "$name"
        ));
    }

    public function viewAction($name)
    {
        return $this->render('AcmeServicesBundle:Default:view.html.twig', array('name' => $name));
    }
}
