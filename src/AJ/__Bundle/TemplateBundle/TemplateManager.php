<?php

namespace AJ\Bundle\TemplateBundle;

use AJ\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;


class TemplateManager
{
	protected $container;
	protected $activeTemplate;
	protected $twig;

	

	public function __construct(ContainerInterface $container)
	{
		$this->container 			= $container;
		$this->twig 				= $container->get('twig');
		$this->activeTemplate 		= $container->get('aj_template.active_template');
		$this->activeTemplate->setBundle($container->get('kernel')->getBundle($container->getParameter('aj_template')['active_template']));
	}



	public function findTemplate($templateName, $groupName = '')
	{
		/*
		$templateName = sprintf('%s:%s:%s.html.twig',
			$this->activeTemplate->getBundleName(),
			ucfirst($groupName),
			str_replace(':', '/', $templateName)
		);
		*/
		$templateName = new TemplateReference(
			$this->activeTemplate->getBundleName(),
			ucfirst($groupName),
			str_replace(':', '/', $templateName),
			'html', 
			'twig'
		);
		return $this->twig->loadTemplate($templateName);
	}

}
