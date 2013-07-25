<?php

namespace AJ\Bundle\ModuleBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Routing;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use AJ\Misc\Arr;
use AJ\Component\HttpFoundation\Params;
use AJ\Component\HttpKernel\Controller\Data\ControllerAttrs;
use AJ\Component\HttpKernel\Controller\Data\ControllerData;

abstract class Controller extends BaseController implements AJModuleControllerInterface, ContainerAwareInterface
{
	protected $ENTITY_MANAGER_NAME 		= "default";
	protected $bundle;
	protected $BUNDLE_NAME;

	public $attr;
	private $globals = array();
	
	
	function __construct()
	{
		
	}
	
	function setContainer(ContainerInterface $container = NULL)
	{
		parent::setContainer($container);

		$ref = new \ReflectionClass($this);
		$controllerNamespace = $ref->getNamespaceName();

		//$name = substr($namespace, 0, strpos($namespace, '\\Controller'));
		foreach ($this->get('kernel')->getBundles() as $bundle)
		{
			if (0 === strpos($controllerNamespace, $bundle->getNamespace())) {
				$this->bundle = $bundle;
				$this->BUNDLE_NAME = $bundle->getName();
				break;
			}
		}


	}
	
	function model($name)
	{
		$repositoryName = $this->bundle->getName() . ':' . $name;

		return $this->getRepository($repositoryName, $this->ENTITY_MANAGER_NAME);
	}
	
	function getRepository($name, $managerName = 'default')
	{
		return $this->getDoctrine()->getRepository($name, $managerName);
	}

	
	function getController($name, $managerName = null)
	{}

	
	function read($name, $managerName = null)
	{
		$repositoryName = $this->BUNDLE_NAME . ':' . $name;

		return $this->getDoctrine()->getRepository($repositoryName, ($managerName ?: $this->ENTITY_MANAGER_NAME));
	}

	

	function __isset($key)
	{
		return $this->_has($key);
	}
	
	function __get($key)
	{
		return $this->_get($key);
	}
	
	function __set($key, $value)
	{
		$this->_set($key, $value);
	}
}
