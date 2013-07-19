<?php

namespace Acme\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexNoNameAction()
    {
    	
		echo get_class($this->get('event_dispatcher'));
		echo "<br><br><br><br>";
        return $this->render('AcmeTestBundle:Default:index.html.twig', array('name' => 'START'));
    }
}
