<?php
/**
 * This file is part of the <name> project.
 *
 * (c) Andraž Jalovec (andraz@cnj.si)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Application\Sonata\PageBundle\DependencyInjection\Compiler\SetPagePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 * References :
 *   bundles : http://symfony.com/doc/current/book/bundles.html
 *
 * @author Andraž Jalovec (andraz@cnj.si)
 */
class ApplicationSonataPageBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SetPagePass());

    }

    public function getParent()
    {
        return 'SonataPageBundle';
    }
}