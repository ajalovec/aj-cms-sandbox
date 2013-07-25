<?php

namespace AJ\Template\BootstrapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AJTemplateBootstrapBundle:Default:index.html.twig', array('name' => $name));
    }
}
