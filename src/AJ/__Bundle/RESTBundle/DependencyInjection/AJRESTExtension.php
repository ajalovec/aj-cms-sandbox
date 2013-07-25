<?php

namespace AJ\Bundle\RESTBundle\DependencyInjection;

use AJ\Component\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Tukaj se zlouda in ureja konfiguracija bundla
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AJRESTExtension extends Extension
{
	const DIR_BUNDLE = __DIR__;

	public function load(array $configs, ContainerBuilder $container)
	{
		parent::load($configs, $container);
		
		$loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.xml');
	}

	

}
