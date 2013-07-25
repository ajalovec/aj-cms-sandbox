<?php

namespace AJ\Bundle\TemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AJ\Component\HttpKernel\Controller\TemplateControllerInterface;

abstract class TemplateController extends Controller implements TemplateControllerInterface
{
    protected $BUNDLE_NAME;

    public function getTemplateBundle()
    {
        return $this->BUNDLE_NAME;
    }

    public function setContainer(ContainerInterface $container = NULL)
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



    /**
     * @Route("/dumpasdasda23423423/{name}", name="___template_dump", defaults={"name" = "Dela"})
     * @Template("AJTemplateBundle:Default:hello.html.twig", vars={"name", "debug"})
     */
    public function indexAction($name, $debug = "")
    {
        $request = $this->getRequest();
    	$debug
    		= "\n getScheme:\t\t" . $request->getScheme()
    		. "\n getHost:\t\t" . $request->getHost()
    		. "\n getHttpHost:\t\t" . $request->getHttpHost()
    		. "\n getSchemeAndHttpHost:\t" . $request->getSchemeAndHttpHost()
    		. "\n getPort:\t\t" . $request->getPort()
    		. "\n getPathInfo:\t\t" . $request->getPathInfo()
    		. "\n getUserInfo:\t\t" . $request->getUserInfo()
    		. "\n"
    		. "\n getScriptName:\t\t" . $request->getScriptName()
    		. "\n getBaseUrl:\t\t" . $request->getBaseUrl()
    		. "\n getBasePath:\t\t" . $request->getBasePath()
    		. "\n"
    		. "\n getRequestUri:\t\t" . $request->getRequestUri()
    		. "\n"
    		. "\n getUriForPath:\t\t" . $request->getUriForPath('/custom/url/name')
    		. "\n getUri:\t\t" . $request->getUri()
    		. "\n getQueryString:\t" . $request->getQueryString()
    		. "\n getContentType:\t\t" . $request->getContentType()
    		. "\n getLocale:\t\t" . $request->getLocale()
    		. "\n getRequestFormat:\t" . $request->getRequestFormat()
    		. "\n getMimeType:\t\t" . $request->getMimeType('html')
    		. "\n getFormat:\t\t" . $request->getFormat('text/html')
    		. "\n getLanguages:\t\t" . print_r($request->getLanguages('text/html'), true)
    		. "\n getCharsets:\t\t" . print_r($request->getCharsets('text/html'), true)
    		. "\n" . print_r($request->server, true)
    		. "\n";
    		
    	
    	$debug = print_r($debug, true);
    	
    	return get_defined_vars();
    }
}
