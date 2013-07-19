<?php

namespace Acme\AssortmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeAssortmentBundle:Default:index.html.twig', array('name' => $name));
    }
}
