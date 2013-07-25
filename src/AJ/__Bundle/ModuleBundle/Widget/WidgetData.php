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

class WidgetData implements WidgetInterface
{
    private $data;
    private $parameters;

	function __construct($row = null, array $parameters = null)
	{
        $this->data         = $row;
        $this->parameters   = $parameters;
    }
    
    public function __isset($fieldName)
    {
        return isset($this->data[$fieldName]);
    }
    
    public function __get($fieldName)
    {
        if(isset($this->data[$fieldName]))
        {
            return $this->data[$fieldName];
        }
        
        return null;
    }
}
