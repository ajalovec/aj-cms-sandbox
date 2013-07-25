<?php

namespace AJ\Bundle\TemplateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use AJ\Bundle\TemplateBundle\DependencyInjection\Compiler\TemplateResourcesPass;

class AJTemplateBundle extends Bundle
{
	
	public function build(ContainerBuilder $container)
    {
    	parent::build($container);
    	
        $container->addCompilerPass(new TemplateResourcesPass());
    }
	
}
