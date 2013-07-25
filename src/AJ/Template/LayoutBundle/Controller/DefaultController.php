<?php

namespace AJ\Template\LayoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AJTemplateLayoutBundle:Default:index.html.twig', array('name' => $name));
    }
}
