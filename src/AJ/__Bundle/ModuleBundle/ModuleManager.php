<?php

namespace AJ\Bundle\ModuleBundle;

use AJ\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use AJ\Misc\Arr;

class ModuleManager
{
	private $bundleMap 			= array();
	private $loadedModules 		= array();
	protected $container;
	protected $kernel;

	

	public function __construct(ContainerInterface $container)
	{
		$this->container 		= $container;
		$this->kernel 			= $container->get('kernel');
	}
	
	/**
	 * Adds bundle to bundleMap
	 *
	 * @param Bundle 	$bundle 	A Bundle instance
	 *
	 * @return ModuleManager A ModuleManager _self instance
	 */
	public function addBundle($bundle)
	{
		if(in_array($bundle, $this->bundleMap) || !($bundle instanceof AJModuleBundleInterface))
		{
			return;
		}

		$this->bundleMap[$bundle->getName()] = $bundle;

		return $this;
	}

	/**
	 * Returns registrated module ids
	 *
	 * @return array Array of module ids
	 */
	public function getModuleIds()
	{
		return array_keys($this->bundleMap);
	}

	/**
	 * Returns loaded module ids
	 *
	 * @return array Array of module ids
	 */
	public function getLoadedModuleIds()
	{
		return array_keys($this->loadedModules);
	}

	/**
	 * Returns Module instane
	 *
	 * @param string 	$bundleName 	The bundle name
	 *
	 * @return Module Module object
	 */
	public function getModule($name)
	{
		extract(static::parseModuleName($name));
		
		if(isset($this->loadedModules[$bundleName]))
		{
			return $this->loadedModules[$bundleName];
		}
		
		if(!isset($this->bundleMap[$bundleName]))
		{
			die("Module {$bundleName} does not exist.");
		}

		$bundle = $this->kernel->getBundle($bundleName);
		$config = $this->container->getParameter('aj_module.manager.config');
		$config = array_replace_recursive($config['defaults'], Arr::get($config, "configs.{$moduleName}", array()));

		$module = new Module($moduleName, $config, $bundle, $this->container);

		return $this->loadedModules[$bundleName] = $module;
	}


	static private function parseModuleName($moduleName)
	{
		$moduleName = substr($moduleName, -6) == 'Bundle' ? substr($moduleName, 0, -6) : $moduleName;
		$moduleName = substr($moduleName, 0, 6) == 'Module' ? substr($moduleName, 6) : $moduleName;
		$bundleName = sprintf('Module%sBundle', $moduleName);
		
		return compact('moduleName', 'bundleName');
	}

	/**
	 * Returns Modules array
	 *
	 * @return array Array of modules
	 */
	public function getAllModules()
	{
		foreach($this->bundleMap as $bundleName => $bundle)
		{
			if(isset($this->loadedModules[$bundle->getName()])) continue;
			$this->getModule(substr($bundleName, 0, -6));
		}

		return $this->loadedModules;
	}
}
