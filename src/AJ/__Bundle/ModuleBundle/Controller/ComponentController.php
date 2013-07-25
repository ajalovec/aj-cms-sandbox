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

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AJ\Misc\Arr;
use AJ\Component\HttpFoundation\Params;
use AJ\Component\HttpKernel\Controller\Data\ControllerAttrs;
use AJ\Component\HttpKernel\Controller\Data\ControllerData;

abstract class ComponentController extends BaseController implements ContainerAwareInterface
{
	public $request;
	public $attr;
	private $globals = array();
	
	
	private function parseNamespace()
	{
		$action = $this->request->get('_controller');
		$action = substr($action, (1 + strrpos($action, ':')));
		
		if($ofs = strrpos($action, 'Action'))
		{
			$action = substr($action, 0, $ofs);
		}
		
		$path = get_class($this);
		// explode _controller name to BUNDLE and CONTROLLER
		list($bundle, $controller) = explode('\\Controller\\', $path, 2);
		//list($bundle, $controller) = array('ProjectBundle', 'ProjectController');
		if($ofs = strrpos($controller, 'Controller'))
		{
			$name = substr($controller, 0, $ofs);
		}
		$name = $this->container->underscore($name);

		return compact('bundle', 'controller', 'name', 'action', 'path');
	}
	
	
	function setContainer(ContainerInterface $container = null)
	{
		parent::setContainer($container);
		
		$this->request		= $this->getRequest();
		
		$params = $this->parseNamespace();
		$this->attr = new ControllerAttrs($this->get('router')->getGenerator());
		$this->attr = $this->attr;
		
		$this->attr->name					= $params['name'];
		$this->attr->route					= '_' . strtolower($params['name']);
		$this->attr->bundle->namespace		= $params['bundle'];
		$this->attr->bundle->name			= (isset($this->bundleName) ? $this->bundleName : substr($params['bundle'], (1 + strrpos($params['bundle'], '\\'))));
		$this->attr->bundle->shortName		= substr($params['bundle'],(1 + strrpos($params['bundle'], '\\')), -6);
	
		$this->attr->bundle->dir			= $this->getBundle()->getPath();
		$this->attr->action->name			= $params['action'];
		$this->attr->action->route			= $this->request->get('_route');
		$this->attr->action->params			= $this->request->get('_route_params', array());
		
		$this->request->attributes->set('ctrl', $this->attr->toArray());
		
		//var_dump($this->attr->bundle->toArray());
		
		if($this->request->get('_route') == '_internal')
		{
			//$this->attr->name					= "asdasd";
	//		var_dump($this->get('controller_name_converter')->parse($this->request->get('_controller')));
		}
		//var_dump($this->get('kernel'));
		//$this->request->attributes->set('ctrl', $this->attr);
		//debug($this->container->getServiceIds());
		//var_dump($params);
		//debug($this->container->getServiceIds());
		//var_dump($this->request->attributes->all());
	}

	public function __xcall($name, $arguments = array())
	{
		$request = $this->getRequest();
		

		$response = new JsonResponse();
		$response->setData(call_user_func_array(array($this, $name), $arguments));
		$response->setCallback($request->query->get('jsonp_callback'));

		//debug($request->attributes->all());
		//debug($name);

		//$response = new Response($json_callback."(".json_encode($data).");"); 
		$response->headers->set('Access-Control-Allow-Origin', '*');
		//$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	/**
	 * Get response variable
	 * $obj->{'var.1.neki.0.test'}
	 * $obj->__get('var.1.neki.0.test', 'default value')
	 * 
	 * @param string 	$key
	 * @param mixed 	$default
	 */
	function _get($key, $default = null)
	{
		return Arr::get($this->globals, $key, $default);
	}
	
	/**
	 * Set response variable
	 * . = separator, @ = array_push
	 * $obj->{'var.@.neki.@.test'} = 'value'
	 * $obj->__set('var.@.neki.@.test', 'value', merge(true|false)
	 * 
	 * @param string 	$key
	 * @param mixed 	$value
	 * @param boolean 	$merge
	 */
	function _set($key, $value, $merge = false)
	{
		Arr::set($this->globals, $key, $value, $merge);
	}
	
	/**
	 * Has response variable
	 * 
	 * @param string $key
	 */
	function _has($key)
	{
		Arr::key_exists($this->globals, $key);
	}
	
	
	
	function getModel($name = null)
	{
		return $this->model($name);
	}
	
	function model($name = null, $managerName = null)
	{
		$repositoryName = is_string($name) ? $name : ($this->attr->bundle->name . ':' . $this->attr->name);
		
		$db = $this->getDoctrine();
		
		return $db->getRepository($repositoryName, $managerName);
	}
	

	function getControllerName($name = null)
	{
		return $this->get('controller_name_converter')->parse($name);
	}
	
	
	
	function getBundle($name = null)
	{
		$name = $name === null ? $this->attr->bundle->name : $name;
		
		return $this->get('kernel')->getBundle($name);
	}
	
	function getNamespace($name = null)
	{
		return $this->getBundle()->getNamespace() . '\\' . trim(str_replace('/', '\\', $name), '\\');
	}
	
	function url($route = null, $params = array(), $absolute = false)
	{
		
		if($route[0] === '@')
		{
			$route = $this->attr->route . '_' . substr($route, 1);
		}
		elseif($route === null)
		{
			$route = $this->attr->route;
			$params = $this->attr->params->toArray();
		}
		
		
		if(is_string($params) && $params[0] != '?')
		{
			parse_str($params, $params);
		}
		
        return $this->get('router')->generate($route, (array) $params, $absolute);
	}
	
	
	function actionRedirect($action = null)
	{
		return $this->redirect($this->url($action));
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
