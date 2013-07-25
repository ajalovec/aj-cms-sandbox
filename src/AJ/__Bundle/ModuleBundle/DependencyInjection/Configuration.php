<?php

namespace AJ\Bundle\ModuleBundle\DependencyInjection;

use AJ\Component\DependencyInjection\Configuration as BaseConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration extends BaseConfiguration
{
    
    protected function buildConfigTree($root)
    {
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $root
            ->children()
                ->append($this->getManagerNode())
                /*
                ->arrayNode('defaults')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity_manager')->defaultValue('default')->end()
                    ->end()
                ->end()
                */
            ->end();
    }
    
    private function getManagerNode()
    {
        $managerNode = static::createNode('manager');

        $managerNode
            ->children()
                ->arrayNode('defaults')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('entity_manager')->defaultValue('default')->end()
                    ->end()
                ->end()
                ->arrayNode('configs')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('entity_manager')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $managerNode;
    }
}
