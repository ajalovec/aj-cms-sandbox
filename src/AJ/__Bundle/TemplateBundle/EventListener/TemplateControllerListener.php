<?php

namespace AJ\Bundle\TemplateBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use AJ\Component\HttpKernel\Controller\TemplateControllerInterface;
use AJ\Template\Twig\Extension\TemplateControllerAwareExtensionInterface;

class TemplateControllerListener
{
	protected $extension;

	public function __construct($extension)
	{
		$this->extension = $extension;
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		$this->setTemplateBundle($this->extension, $event);
	}

	private function setTemplateBundle($extension, $event)
	{

		if($extension instanceof TemplateControllerAwareExtensionInterface)
		{
			list($controller, $action) = $event->getController();

			if($controller instanceof TemplateControllerInterface)
			{
				$extension->setTemplateBundle($controller->getTemplateBundle());
				if(method_exists($extension, 'setController'))
				{
					$extension->setController($controller, $action);
					return true;
				}
			}
		}

		return false;
	}

}
