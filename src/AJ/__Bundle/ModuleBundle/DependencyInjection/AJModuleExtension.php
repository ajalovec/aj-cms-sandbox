<?php

namespace AJ\Bundle\ModuleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use AJ\Component\DependencyInjection\Extension;

/**
 * Tukaj se zlouda in ureja konfiguracija bundla
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AJModuleExtension extends Extension
{

	public function load(array $configs, ContainerBuilder $container)
	{
		// returns merged and validated config array
		$config = parent::getConfig($configs);
        
		$container->setParameter('aj_module.manager.config', $config['manager']);
		//$container->setParameter('aj_module.manager.configs', $config['manager']['configs']);
		$loader = parent::getContainerLoader(__DIR__.'/../Resources/config', $container);
		
		//$loader->load('twig.xml');
		//$loader->load('menus.xml');
		$loader->load('services.xml');
	}

}
