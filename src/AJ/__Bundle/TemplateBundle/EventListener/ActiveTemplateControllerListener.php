<?php

namespace AJ\Bundle\TemplateBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use AJ\Component\HttpKernel\Controller\TemplateControllerInterface;
use AJ\Bundle\TemplateBundle\ActiveTemplate;

class ActiveTemplateControllerListener
{
	protected $extension;
	protected $kernel;

	public function __construct(ActiveTemplate $extension, $kernel = null)
	{
		$this->kernel = $kernel;
		$this->extension = $extension;
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		list($controller, $action) = $event->getController();

		if($controller instanceof TemplateControllerInterface)
		{
			$bundle = $this->kernel->getBundle($controller->getTemplateBundle());

			$this->extension->setBundle($bundle);
		}
	}

}
