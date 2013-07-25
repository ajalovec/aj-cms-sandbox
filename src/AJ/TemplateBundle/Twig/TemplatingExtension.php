<?php

namespace AJ\Bundle\TemplateBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use CG\Core\ClassUtils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AJ\Bundle\TemplateBundle\ActiveTemplate;


class TemplatingExtension extends \Twig_Extension
{
	protected $loader;
	protected $activeTemplate;
	protected $container;

	public function __construct(ContainerInterface $container, FilesystemLoader $loader, ActiveTemplate $activeTemplate)
	{
		$this->container = $container;
		$this->activeTemplate = $activeTemplate;
		$this->loader = $loader;
	}

	
	public function getFunctions()
	{
		return array(
			'component' => new \Twig_Function_Method($this, 'renderComponent', array('is_safe' => array('html'))),
			'renderTemplate' => new \Twig_Function_Method($this, 'renderTemplate', array('is_safe' => array('html'))),
			'getTemplate' => new \Twig_Function_Method($this, 'getTemplate', array('is_safe' => array('html'))),
			'getTemplatePath' => new \Twig_Function_Method($this, 'getTemplatePath', array('is_safe' => array('html'))),
			'getBundle' => new \Twig_Function_Method($this, 'getTemplatePath', array('is_safe' => array('html'))),

			'getDoctype' => new \Twig_Function_Method($this, 'getDoctype', array('is_safe' => array('html'))),
		);
	}

	
	public function renderComponent($componentName, $parameters = array())
	{
		return $this->container->get('aj_template.manager')->findTemplate($componentName, 'Component')->render($parameters);
	}
	

	public function renderTemplate($templateName, $parameters = array())
	{
		return $this->getTemplate($templateName)->render($parameters);
	}

	public function getTemplate($templateName)
	{
		$template = $this->container->get('twig')->loadTemplate($this->getTemplatePath($templateName));

		return $template;
	}

	public function getTemplatePath($path)
	{
		$templateGroup = '';
		$templatePath = ltrim($path, ':');
		
			
		if(1 < strpos($templatePath, ':'))
		{
			list($templateGroup, $templatePath) = explode(':', $templatePath, 2);
		}

		$templatePath = str_replace(':', '/', $templatePath);

		return sprintf('%s:%s:%s', $this->activeTemplate->getBundleName(), $templateGroup, $templatePath);
	}
	

	public function getDoctype($name = 'base')
	{
		return 'AJTemplateBundle::doctype/' . $name . '.html.twig';
	}

	

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'aj_template.twig.templating';
	}
}
