<?php
namespace Acme\ElFinderBundle\Twig\Extension;

use FM\ElfinderBundle\Twig\Extension\FMElfinderTinymceExtension as FMElfinderTinymceBaseExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 *
 */
class FMElfinderTinymceExtension extends FMElfinderTinymceBaseExtension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFilters()
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'elfinder_tinymce_init_extended' => new \Twig_Function_Method($this, 'tinymce_extended', array('is_safe' => array('html')))
        );
    }

    /**
     *
     */
    public function tinymce_extended()
    {
        return $this->container->get('templating')->render('AcmeElFinderBundle:Elfinder:_tinymce_extended.html.twig');
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'fm_tinymce_init_extended';
    }
}