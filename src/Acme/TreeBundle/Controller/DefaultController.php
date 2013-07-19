<?php

namespace Acme\TreeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeTreeBundle:Default:index.html.twig', array('name' => $name));
    }
}
