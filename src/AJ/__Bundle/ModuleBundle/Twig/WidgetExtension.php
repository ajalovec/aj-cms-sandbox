<?php

namespace AJ\Bundle\ModuleBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use CG\Core\ClassUtils;

use AJ\Bundle\TemplateBundle\ActiveTemplate;
use Symfony\Component\DependencyInjection\ContainerInterface;


class WidgetExtension extends \Twig_Extension
{
	protected $activeTemplate;
	protected $moduleManager;
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->activeTemplate = $container->get('aj_template.active_template');
		$this->moduleManager = $container->get('aj_module.manager');
	}

	
	public function getFunctions()
	{
		return array(
			
			'template_from_string' => new \Twig_Function_Method($this, 'renderString', array('is_safe' => array('html'))),
			'widget' => new \Twig_Function_Method($this, 'renderWidget', array('is_safe' => array('html'))),
		);
	}


	public function getWidget($name)
	{

		
		//var_dump($twig->getBaseTemplateClass());
	}
	public function renderString($string)
	{
		return $string;
		
		//var_dump($twig->getBaseTemplateClass());
	}


	public function renderWidget($name, $parameters = array(), $theme = null)
	{
		list($bundleName, $widgetName) = explode(':', $name, 2);

		return $this->moduleManager->getModule($bundleName)->getWidget($widgetName, $parameters);
	}



	public function getView($name)
	{
		//error_log("Pokazi template name".print_r($this->templateBundle,1));
		if(!isset($this->templateBundle))
		{
			//hocem dobi podatek kje je napaka oz kateri kontroler ni pravilno definiran
			$reflector = new \ReflectionObject($this->controller);
			die("You must specify templateBundle in your controller in ". $reflector->getFilename());
			
		}
		
		return $this->templateBundle . '::template/view/' . $name . '.html.twig';
	}
	
	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'aj_module.twig.widget';
	}
}
