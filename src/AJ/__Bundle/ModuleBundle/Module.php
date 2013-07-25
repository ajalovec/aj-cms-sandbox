<?php

namespace AJ\Bundle\ModuleBundle;

use AJ\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use AJ\Bundle\ModuleBundle\Widget\Widget;

class Module
{
	private $loadedModels = array();
	private $loadedWidgets = array();
	private $ENTITY_MANAGER_NAME 	= "t2_reality";
	private $config;
	private $_em;

	private $moduleName;
	private $bundle;
	private $twig;
	private $container;

	
	public function __construct($moduleName, array $config, AJModuleBundleInterface $bundle, ContainerInterface $container)
	{
		$this->moduleName	= $moduleName;
		$this->config		= $config;
		$this->bundle 		= $bundle;
		$this->container 	= $container;
		$this->twig 		= $container->get('twig');

	}


	public function getName()
	{
		return $this->moduleName;
	}

	public function getConfig($key, $default = null)
	{
		return Arr::get($this->config, $key, $default);
	}


	public function getEntityManager()
	{
		if(isset($this->_em))
		{
			return $this->_em;
		}

		return $this->_em = $this->container->get('doctrine')->getManager($this->getConfig('entity_manager'));
	}


	public function getData($widgetName, $arguments = array())
	{
		list($modelName, $functionName) = explode(':', $widgetName, 2);

		$model = $this->getModel($modelName);
		$arguments = is_array($arguments) ? $arguments : (array) $arguments;

		return call_user_func_array(array($model, $functionName), $arguments);
	}


	public function getWidget($widgetName, $parameters = array())
	{
		$arguments = isset($parameters['arguments']) ? $parameters['arguments'] : array();
		$data = $this->getData($widgetName, $arguments);

		$template = $this->getTemplate($widgetName);

		return $template->render(compact('data'));

		//$d = new \stdClass();
		//$d->globals = $twig->getGlobals()['assetic'];
		//$d->functions = $twig->getFunctions();
		//$d->blocks = $template->getBlockNames();
		
		//var_dump($d);
	}

	public function getTemplate($widgetName)
	{
		list($modelName, $actionName) = explode(':', $widgetName, 2);

		$templateName = sprintf('%s:%s:%s.html.twig', $this->bundle->getName(), $modelName, $actionName);

		return $this->twig->loadTemplate($templateName);
	}

	public function getModel($modelName)
	{
		$aname = explode(':', $modelName);

		if(isset($this->loadedModels[$modelName]))
		{
			return $this->loadedModels[$modelName];
		}

		$modelClassName = sprintf('%s\\Api\\%sModel', $this->bundle->getNamespace(), $modelName);
		
		$model = new $modelClassName($this->container);

		return $this->loadedModels[$modelName] = $model;
	}


	public function getRepository($modelName, $managerName = null)
	{
		$repositoryName = $this->bundle->getName() . ':' . $modelName;

		return $this->getService('doctrine')->getRepository($repositoryName, ($managerName ?: $this->ENTITY_MANAGER_NAME));

	}


	protected function getService($name)
	{
		return $this->container->get($name);
	}
	protected function hasService($name)
	{
		return $this->container->has($name);
	}


}
