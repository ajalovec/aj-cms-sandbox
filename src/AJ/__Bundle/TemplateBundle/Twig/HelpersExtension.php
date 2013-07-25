<?php

namespace AJ\Bundle\TemplateBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CG\Core\ClassUtils;

use AJ\Bundle\TemplateBundle\ActiveTemplate;

class HelpersExtension extends \Twig_Extension
{
	private $activeTemplate;
	protected $container;

	public function __construct(ActiveTemplate $activeTemplate, ContainerInterface $container)
	{
		$this->activeTemplate = $activeTemplate;
		$this->container = $container;
	}

	
	public function getFunctions()
	{
		return array(
			'web_path' => new \Twig_Function_Method($this, 'getWebPath', array('is_safe' => array('html'))),
			'tpl_path' => new \Twig_Function_Method($this, 'getTplAssetsUrl', array('is_safe' => array('html'))),
			'theme_path' => new \Twig_Function_Method($this, 'getThemeAssetsUrl', array('is_safe' => array('html'))),
			'attr_class' => new \Twig_Function_Method($this, 'printAttributeClass', array('is_safe' => array('html'))),
			'attrs' => new \Twig_Function_Method($this, 'getAttributes', array('is_safe' => array('html'))),
			'icon' => new \Twig_Function_Method($this, 'htmlIcon', array('is_safe' => array('html'))),
			'img' => new \Twig_Function_Method($this, 'htmlImage', array('is_safe' => array('html'))),
			'imgBlank' => new \Twig_Function_Method($this, 'htmlImgBlank', array('is_safe' => array('html'))),
		);
	}

	public function htmlImgBlank($attrs = array())
	{	
		return $this->htmlImage($this->getTplAssetsUrl('images/blank.gif'), $attrs);

		return sprintf('<img src="%s" %s />', $src, $attrs);
	}

	public function htmlImage($src, $attrs = array())
	{	
		if(is_string($attrs))
		{
			parse_str($attrs, $attrs);
		}
		$attrs = $this->getAttributes($attrs);
		$attrs->class = 'img ' . (isset($attrs->class) ? $attrs->class : '');

		return sprintf('<img src="%s" %s />', $src, $attrs);
	}
	public function htmlIcon($name, $useImage = false)
	{	
		$format = '<i class="icon icon-%s"></i>';
		return sprintf($format, $name);
	}
	
	public function getWebPath($path, $relative = false)
	{	
		return $this->activeTemplate->getUrl($relative) . trim($path, '/');
	}

	public function getTplAssetsUrl($path, $relative = false)
	{	
		return $this->activeTemplate->getAssetUrl($relative) . trim($path, '/');
	}

	public function getThemeAssetsUrl($path, $relative = false)
	{	
		return $this->activeTemplate->getThemeAssetUrl($relative) . trim($path, '/');
	}
	
	public function printAttributeClass()
	{
		$class = implode(' ', func_get_args());
		$sanitizedClass = preg_replace('/\s+/i', ' ', trim($class));

		return sprintf('class="%s"', $sanitizedClass);
	}
	
	public function getAttributes($attributes = null)
	{
		$args = func_get_args();
		$attrs = new \AJ\Template\Html\element\Attrs();
		while(count($args) > 0)
		{
			$attrs->add(array_shift($args));
		}
		
		return $attrs;
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
		return 'aj.twig.helpers';
	}
}
