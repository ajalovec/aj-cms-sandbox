<?php

namespace AJ\Bundle\TemplateBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TemplateResourcesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
    	/*
    	if($container->has('aj_template.name_parser'))
    	{
        	$container->setAlias('templating.name_parser', 'aj_template.name_parser');
    	}
*/
		if($container->has('aj_template.name_parser'))
    	{
            $container->setAlias('templating.name_parser', 'aj_template.name_parser');
        }
        
        $container->setAlias('assetic.twig_extension', 'aj_template.assetic.twig_extension');

        //$container->setAlias('templating.cache_warmer.template_paths', 'liip_theme.templating.cache_warmer.template_paths');

    }
}
