<?php

namespace Application\Sonata\PageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Changes the Router implementation.
 *
 */
class SetPagePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        return;
        // only replace the default router by overwriting the 'router' alias if config tells us to
        if (true === $container->has('sonata.page.admin.page')) {
            $container->setAlias('sonata.page.admin.page', 'app_sonata.page.admin.page');
        }

        if (true === $container->has('sonata.page.admin.block')) {
            $container->setAlias('sonata.page.admin.block', 'app_sonata.page.admin.block');
        }
    	return;

    }
}
