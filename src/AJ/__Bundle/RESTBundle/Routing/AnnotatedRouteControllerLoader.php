<?php

namespace AJ\Bundle\RESTBundle\Routing;

use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader as BaseAnnotatedRouteControllerLoader;
use Symfony\Component\Routing\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as FrameworkExtraBundleRoute;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * AnnotatedRouteControllerLoader is an implementation of AnnotationClassLoader
 * that sets the '_controller' default based on the class and method names.
 *
 * It also parse the @Method annotation.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class AnnotatedRouteControllerLoader extends BaseAnnotatedRouteControllerLoader
{
    /**
     * Configures the _controller default parameter and eventually the _method
     * requirement of a given Route instance.
     *
     * @param Route            $route  A Route instance
     * @param ReflectionClass  $class  A ReflectionClass instance
     * @param ReflectionMethod $method A ReflectionClass method
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        $classParams = null;

        parent::configureRoute($route, $class, $method, $annot);

        return;
        if($classAnnot = $this->reader->getClassAnnotation($class, $this->routeAnnotationClass))
        {
            $classParams = array(
                $classAnnot->getName(),
                $classAnnot->getPattern(),
                $classAnnot->getRequirements(),
                $classAnnot->getDefaults(),
                $classAnnot->getOptions()
            );
        }

        debug(array(
            $classParams,
            $annot->getName(),
            $annot->getPattern(),
            $annot->getRequirements(),
            $annot->getDefaults(),
            $annot->getOptions(),
        ));
        //debug(array($route->getDefaults(), $class, $method, $annot));
        /*
        // controller
        if ($classAnnot instanceof FrameworkExtraBundleRoute && $service = $classAnnot->getService()) {
            $route->setDefault('_controller', $service.':'.$method->getName());
        } else {
            $route->setDefault('_controller', $class->getName().'::'.$method->getName());
        }

        // requirements (@Method)
        foreach ($this->reader->getMethodAnnotations($method) as $configuration) {
            if ($configuration instanceof Method) {
                $route->setRequirement('_method', implode('|', $configuration->getMethods()));
            }
        }
        */
    }

    /*
     * Makes the default route name more sane by removing common keywords.
     *
     * @param ReflectionClass  $class  A ReflectionClass instance
     * @param ReflectionMethod $method A ReflectionMethod instance
     * @return string
    protected function getDefaultRouteName(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $routeName = parent::getDefaultRouteName($class, $method);

        return preg_replace(array(
            '/(bundle|controller)_/',
            '/action(_\d+)?$/',
            '/__/'
        ), array(
            '_',
            '\\1',
            '_'
        ), $routeName);
    }
     */
}
