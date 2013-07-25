<?php

namespace AJ\Bundle\ModuleBundle\Widget;

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

class Widget implements WidgetInterface
{
    const TYPE_AUTO     = 1;
    const TYPE_CONTENT  = 2;
    const TYPE_LIST     = 3;
    const TYPE_GRID     = 4;
    const TYPE_TABLE    = 5;

    protected $type = static::TYPE_AUTO;
    
    private $twigEngine;
    protected $data;
    protected $parameters;

    

	function __construct($twigEngine, $data = null, array $parameters = null)
	{
        $this->twigEngine   = $twigEngine;
        $this->data         = $data;
        $this->parameters   = $parameters;

    }
    
    public function getData($actionName, $arguments = array())
    {
        $model = $this->getModel($this->modelName);
        $functionName = sprintf('%sAction', $actionName);
        $arguments = is_array($arguments) ? $arguments : (array) $arguments;

        return call_user_func_array(array($model, $functionName), $arguments);
    }
}
