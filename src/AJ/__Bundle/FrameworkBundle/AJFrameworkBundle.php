<?php

namespace AJ\Bundle\FrameworkBundle;

use AJ\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use AJ\Bundle\FrameworkBundle\DependencyInjection\Compiler\ThemeCompilerPass;
use AJ\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplateResourcesPass;

class AJFrameworkBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        //$container->addCompilerPass(new ThemeCompilerPass());
        //$container->addCompilerPass(new TemplateResourcesPass());
    }
}
