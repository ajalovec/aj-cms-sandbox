<?php

namespace AJ\Bundle\ModuleBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\HttpKernel\KernelInterface;
use AJ\Bundle\ModuleBundle\ModuleManager;
use AJ\Bundle\ModuleBundle\AJModuleBundleInterface;



class ControllerListener
{
	protected $kernel;
	protected $moduleManager;

	
	public function __construct(KernelInterface $kernel, ModuleManager $moduleManager)
	{
		$this->kernel = $kernel;
		$this->moduleManager = $moduleManager;
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		//$event->getController();
		foreach($this->kernel->getBundles() as $bundle)
		{
			if($bundle instanceof AJModuleBundleInterface)
			{
				//debug($bundle->getName());
			}
		}
	}

}
