<?php

namespace AJ\Bundle\ModuleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use AJ\Bundle\ModuleBundle\ModuleManager;
use AJ\Bundle\ModuleBundle\AJModuleBundleInterface;


class AJModuleBundle extends Bundle
{
	protected $container;

	public function build(ContainerBuilder $container)
	{
		$this->container = $container;
		//debug($container->getParameterBag()->all());
		parent::build($container);

	}
	
	public function getParent()
	{
		return null;
		return "DoctrineBundle";
	}
	
	public function boot()
	{
		parent::boot();
		
		$this->registerModuleBundles();
	}


	public function registerModuleBundles()
	{
		//$event->getController();
		$module_manager = $this->container->get('aj_module.manager');

		foreach($this->container->get('kernel')->getBundles() as $bundle)
		{
			if($bundle instanceof AJModuleBundleInterface)
			{
				$module_manager->addBundle($bundle);
			}
		}
	}
}
