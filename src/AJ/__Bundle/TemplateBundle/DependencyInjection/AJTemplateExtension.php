<?php

namespace AJ\Bundle\TemplateBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use AJ\Component\DependencyInjection\Extension;

/**
 * Tukaj se zlouda in ureja konfiguracija bundla
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AJTemplateExtension extends Extension
{

	public function load(array $configs, ContainerBuilder $container)
	{
		// returns merged and validated config array
		$config = parent::getConfig($configs);
		$container->setParameter('aj_template', $config);

		parent::registerAssets($container, get_class());

		$loader = parent::getContainerLoader(__DIR__.'/../Resources/config', $container);
		
		$loader->load('assetic.xml');
		//$loader->load('templating.xml');
		$loader->load('twig.xml');
		$loader->load('menus.xml');
		$loader->load('services.xml');
	}

}
