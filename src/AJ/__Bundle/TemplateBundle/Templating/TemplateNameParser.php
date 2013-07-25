<?php

namespace AJ\Bundle\TemplateBundle\Templating;

use Symfony\Component\Templating\TemplateReferenceInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateNameParser as BaseTemplateNameParser;

use AJ\Template\Twig\Extension\TemplateControllerAwareExtensionInterface;
use AJ\Component\HttpKernel\Controller\TemplateControllerInterface;

/**
 * TemplateNameParser converts template names from the short notation
 * "bundle:section:template.format.engine" to TemplateReferenceInterface
 * instances.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TemplateNameParser extends BaseTemplateNameParser implements TemplateControllerAwareExtensionInterface
{
	protected $aj_templateBundle;

	public function isTemplate()
	{
		return (is_string($this->aj_templateBundle) and strlen($this->aj_templateBundle) > 0 ? true : false);
	}

	public function getTemplateBundle()
	{
		return $this->aj_templateBundle ?: 'AJTemplateBundle';
	}
	public function setTemplateBundle($templateBundle)
	{
		$this->aj_templateBundle = $templateBundle;
	}


	private function parseTemplate($name)
	{
		$parts = explode(':', $name);
		if (3 !== count($parts)) {
			throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "bundle:section:template.format.engine").', $name));
		}

		$elements = explode('.', $parts[2]);
		if (3 > count($elements)) {
			throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "bundle:section:template.format.engine").', $name));
		}
		$engine = array_pop($elements);
		$format = array_pop($elements);
		
		$template = new TemplateReference($this->getTemplateBundle(), $parts[0], $parts[1], implode('.', $elements), $format, $engine);

		if ($template->get('bundle')) {
			try {
				$this->kernel->getBundle($template->get('bundle'));
			} catch (\Exception $e) {
				throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid.', $name), 0, $e);
			}
		}
		
		return $this->cache[substr($name, 1)] = $template;

	}

	public function parse($name)
	{
		if(is_string($name) && $name[0] == '@')
		{
			//debug("aaaaaa {$name}");
			return $this->parseTemplate($name);
		}
		//debug("bbb");
		return parent::parse($name);

	}

}
