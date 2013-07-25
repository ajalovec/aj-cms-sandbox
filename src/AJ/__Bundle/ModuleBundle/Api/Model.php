<?php

namespace AJ\Bundle\ModuleBundle\Api;

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

use AJ\Misc\Arr;
use AJ\Component\HttpFoundation\Params;
use AJ\Component\HttpKernel\Controller\Data\ControllerAttrs;
use AJ\Component\HttpKernel\Controller\Data\ControllerData;

abstract class Model implements ModelInterface
{
	protected $ENTITY_MANAGER_NAME 		= "default";
	protected $bundle;
	protected $BUNDLE_NAME;

	public $attr;
	private $globals = array();
	
	
	function __construct(ContainerInterface $container = NULL)
	{
		$this->container = $container;
		$ref = new \ReflectionClass($this);
		$controllerNamespace = $ref->getNamespaceName();

		//$name = substr($namespace, 0, strpos($namespace, '\\Controller'));
		foreach ($this->getService('kernel')->getBundles() as $bundle)
		{
			if (0 === strpos($controllerNamespace, $bundle->getNamespace())) {
				$this->bundle = $bundle;
				$this->BUNDLE_NAME = $bundle->getName();
				break;
			}
		}

		}
		
	function getBundleName()
    {
        return $this->bundle->getName();;
    }
    
    function repository($name)
    {
        return $this->model($name);
    }
    
	
	function model($name)
	{
		$repositoryName = $this->bundle->getName() . ':' . $name;

		return $this->getRepository($repositoryName, $this->ENTITY_MANAGER_NAME);
	}
	
	function getRepository($name, $managerName = 'default')
	{
		return $this->getService('doctrine')->getRepository($name, $managerName);
	}

	
	

	/**
     * Shortcut to return the request service.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->getService('request');
    }
    
	/**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string|FormTypeInterface $type    The built type of the form
     * @param mixed                    $data    The initial data for the form
     * @param array                    $options Options for the form
     *
     * @return Form
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->getService('form.factory')->create($type, $data, $options);
    }

	/**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The renderer view
     */
    public function renderView($view, array $parameters = array())
    {
        return $this->getService('templating')->render($view, $parameters);
    }

	/**
     * Generates a URL from the given parameters.
     *
     * @param string  $route      The name of the route
     * @param mixed   $parameters An array of parameters
     * @param Boolean $absolute   Whether to generate an absolute URL
     *
     * @return string The generated URL
     */
    public function generateUrl($route, $parameters = array(), $absolute = false)
    {
        return $this->getService('router')->generate($route, $parameters, $absolute);
    }


    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->hasService('security.context')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->getService('security.context')->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }


    /**
     * Returns true if the service id is defined.
     *
     * @param string $id The service id
     *
     * @return Boolean true if the service id is defined, false otherwise
     */
    public function hasService($id)
    {
        return $this->container->has($id);
    }

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    public function getService($id)
    {
        return $this->container->get($id);
    }
}
