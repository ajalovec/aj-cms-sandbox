<?php

namespace AJ\Bundle\TemplateBundle\Twig\Builder;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use CG\Core\ClassUtils;

use AJ\Bundle\TemplateBundle\Controller\TemplateControllerInterface     as AJTemplateControllerInterface;

class DemoExtension extends \Twig_Extension implements Interface
{
	protected $loader;
	protected $controller;

	public function __construct(FilesystemLoader $loader)
	{
		$this->loader = $loader;
	}

	public function setController(AJTemplateControllerInterface $controller)
	{
		$this->controller = $controller;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return array(
			'ajTest' => new \Twig_Function_Method($this, 'ajTest'),
		);
	}

	public function ajTest($nevem)
	{
		//vendor\twig\twig\lib\Twig\Extension.php
		$params['getTokenParsers'] = $this->getTokenParsers();
		$params['getNodeVisitors'] = $this->getNodeVisitors();
		$params['getFilters'] = $this->getFilters();
		$params['getTests'] = $this->getTests();
		$params['getFunctions'] = $this->getFunctions();
		$params['getOperators'] = $this->getOperators();
		$params['getGlobals'] = $this->getGlobals();
		$params['getBlockNames'] = $nevem->getBlockNames();

		//return '{{ css_filters }}';
		return var_dump($params);
	}

	public function getDoctype($name = 'base')
	{
		return 'AJTemplateBundle::doctype/' . $name . '.html.twig';
	}

	public function getTemplate($name)
	{
		if(!isset($this->controller[0]->templateName))
		{
			die("You must specify templateName in your controller.");
		}
		return $this->controller[0]->templateName . '::' . $name . '.html.twig';
	}


	public function getView($name)
	{
		if(!isset($this->controller[0]->templateName))
		{
			die("You must specify templateName in your controller.");
		}
		return $this->controller[0]->templateName . '::template/view/' . $name . '.html.twig';
	}






	public function getCode($template)
	{
		$controller = htmlspecialchars($this->getControllerCode(), ENT_QUOTES, 'UTF-8');
		$template = htmlspecialchars($this->getTemplateCode($template), ENT_QUOTES, 'UTF-8');
		
		// remove the code block
		
		$template = str_replace('{% set code = code(_self) %}', '', $template);
		
		return <<<EOF
<p><strong>Controller Code</strong></p>
<pre>$controller</pre>

<p><strong>Template Code</strong></p>
<pre>$template</pre>
EOF;
	}

	protected function getControllerCode()
	{
		$class = get_class($this->controller[0]);
		if (class_exists('CG\Core\ClassUtils')) {
			$class = ClassUtils::getUserClass($class);
		}

		$r = new \ReflectionClass($class);
		$m = $r->getMethod($this->controller[1]);

		$code = file($r->getFilename());

		return '    '.$m->getDocComment()."\n".implode('', array_slice($code, $m->getStartline() - 1, $m->getEndLine() - $m->getStartline() + 1));
	}

	protected function getTemplateCode($template)
	{
		return $this->loader->getSource($template->getTemplateName());
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'aj_template';
	}
}
