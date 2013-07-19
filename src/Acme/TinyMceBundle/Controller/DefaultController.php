<?php

namespace Acme\TinyMceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AcmeTinyMceBundle:Default:index.html.twig', array('name' => $name));
    }
}
