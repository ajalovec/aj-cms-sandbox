<?php

namespace Acme\ElFinderBundle\Controller;
use FM\ElfinderBundle\Controller\ElfinderController as ElfinderBaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ElfinderController extends ElfinderBaseController
{
    public function showExtendedAction()
    {
        $parameters = $this->container->getParameter('fm_elfinder');
        $editor = $parameters['editor'];
        $locale = $parameters['locale'];
        $fullscreen = $parameters['fullscreen'];
		
        return $this->render('AcmeElFinderBundle:Elfinder:tinymce_extended.html.twig', array(
            'locale' => $locale,
            'tinymce_popup_path' => $this->getAssetsUrl($parameters['tinymce_popup_path'])
        ));
    }
}
