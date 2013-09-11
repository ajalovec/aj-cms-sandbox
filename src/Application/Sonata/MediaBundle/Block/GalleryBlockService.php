<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\Model\GalleryInterface;

use Sonata\MediaBundle\Block\GalleryBlockService as BaseGalleryBlockService;

class GalleryBlockService extends BaseGalleryBlockService
{
   public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {   
        parent::buildEditForm($formMapper, $block);
    }
    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'gallery'   => false,
            'title'     => false,
            'context'   => false,
            'format'    => false,
            'pauseTime' => 3000,
            'animSpeed' => 300,
            'startPaused'  => false,
            'directionNav' => true,
            'progressBar'  => true,
            'template'     => 'SonataMediaBundle:Block:block_gallery.html.twig',
            'galleryId'    => null
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getStylesheets($media)
    {
        return array(
            $this->container->getParameter('base_path') . '/bundles/sonatamedia/nivo-gallery/nivo-gallery.css'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts($media)
    {
        return array(
            $this->container->getParameter('base_path') . '/bundles/sonatamedia/nivo-gallery/jquery.nivo.gallery.js'
        );
    }

}
