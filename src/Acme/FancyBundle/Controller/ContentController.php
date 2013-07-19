<?php

namespace Acme\FancyBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentController extends Controller
{
    public function contentAction($id,$filePath)
    {
    	$recommendRepo =$this->getDoctrine()->getRepository('AcmeRecommendBundle:Recommend');
    	$file = $recommendRepo->find($id);
		
    	//return new Response('<img src="{{ asset(recommend.webPath) }}" alt="'.$file->getTitle().'"/>');
        return $this->render('AcmeFancyBundle::content.html.twig', array('file' => $file));
    }
}
