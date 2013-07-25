<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AJ\Bundle\TemplateBundle\Twig;

use Symfony\Bundle\AsseticBundle\Twig\AsseticExtension as BaseAsseticExtension;

use Assetic\Factory\AssetFactory;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Assetic\ValueSupplierInterface;

use AJ\Bundle\TemplateBundle\ActiveTemplate;
use AJ\Bundle\TemplateBundle\Assetic\AsseticTokenParser;
/**
 * Template Assetic extension.
 *
 * @author Andraž Jalovec <andraz@cnj.si>
 */
class AsseticExtension extends BaseAsseticExtension
{
	protected $templateBundle;
	protected $controller;
	protected $action;
	private $activeTemplate;

	public function __construct(ActiveTemplate $activeTemplate,
								AssetFactory $factory,
							 	TemplateNameParserInterface $templateBundleParser,
							 	$useController = false,
							 	$functions = array(),
							 	$enabledBundles = array(),
							 	ValueSupplierInterface $valueSupplier = null
							) {
		$this->activeTemplate = $activeTemplate;
		parent::__construct($factory, $templateBundleParser, $useController, $functions, $enabledBundles, $valueSupplier);
	}
	
	public function getTokenParsers()
    {
        return array_merge(parent::getTokenParsers(), array(
            $this->createTokenParser('tpl_stylesheets', 'css/*.css'),
            $this->createTokenParser('tpl_javascripts', 'js/*.js'),
            //$this->createTokenParser('image', 'images/*', true),
        ));
    }
    
    private function createTokenParser($tag, $output, $single = false)
    {
        $tokenParser = new AsseticTokenParser($this->factory, $tag, $output, $single, array('package'));
        $tokenParser->setTemplateNameParser($this->templateNameParser);
        $tokenParser->setActiveTemplate($this->activeTemplate);
        $tokenParser->setEnabledBundles($this->enabledBundles);

        return $tokenParser;
    }
/*
*/
	public function getNodeVisitors()
	{
		
		return parent::getNodeVisitors();
	}

	public function getGlobals()
	{
		return parent::getGlobals();
	}

	
/*


	private function createTokenParser($tag, $output, $single = false)
	{
		$tokenParser = new AsseticTokenParser($this->factory, $tag, $output, $single, array('package'));
		$tokenParser->setTemplateNameParser($this->templateBundleParser);
		$tokenParser->setEnabledBundles($this->enabledBundles);

		return $tokenParser;
	}
	*/
}
