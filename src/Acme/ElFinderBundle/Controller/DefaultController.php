<?php

namespace Acme\ElFinderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeElFinderBundle:Default:index.html.twig', array('name' => $name));
    }
}
