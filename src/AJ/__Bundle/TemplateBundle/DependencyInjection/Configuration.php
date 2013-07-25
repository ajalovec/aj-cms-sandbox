<?php

namespace AJ\Bundle\TemplateBundle\DependencyInjection;

use AJ\Component\DependencyInjection\Configuration as BaseConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration extends BaseConfiguration
{
	
	public function buildConfigTree($root)
	{
		// Here you should define the parameters that are allowed to
		// configure your bundle. See the documentation linked above for
		// more information on that topic.

		$root
			->children()
			->scalarNode('active_template')->end()
			->scalarNode('test')->defaultValue('bar')->end()
			->end();
	}
	
}
