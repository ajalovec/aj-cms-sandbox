<?php

namespace Acme\TinyMceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acme_tiny_mce');

		$defaults = array();
        $rootNode
            ->children()
	            ->booleanNode('relative_urls')
	            //	->defaultTrue()
	            ->end()
	            ->booleanNode('remove_script_host')
	            //	->defaultTrue()
	            ->end()
				->scalarNode('document_base_url')
				//	->defaultNull()
				->end()
                ->arrayNode('theme')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->useAttributeAsKey('name')
                        ->beforeNormalization()
                            ->always()
                            ->then(function($array) use ($defaults) {
                                // Merge default values with values from the config
                                if (is_array($array)) {
                                    // Excepted values
                                    $unchangeableKeys = array('language');
                                    foreach ($array as $k => $v) {
                                        if (in_array($k, $unchangeableKeys)) {
                                            continue;
                                        }
                                        $defaults[$k] = $v;
                                    }
                                }

                                return $defaults;
                            })
                        ->end()
                        ->prototype('variable')->end()
                    ->end()
                    // Add default theme if it doesn't set
                    ->defaultValue(array('simple' => $defaults))
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
