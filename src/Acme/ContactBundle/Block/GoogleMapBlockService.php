<?php
/*
	Blok google maps
*/
namespace Acme\ContactBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

class GoogleMapBlockService extends BaseBlockService
{	
    public function execute(BlockInterface $block, Response $response = null)
    {
		return $this->renderResponse('AcmeContactBundle:Block:google.html.twig', array(
			'block' => $block
		));
    }
    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {

    }
    public function getJs($media)
    {
        return array('http://maps.google.com/maps/api/js?sensor=false','bundles/acmecontact/js/google-map.js');
    }
    public function getCss($media)
    {
        return array('bundles/acmecontact/css/google-map.css');
    }

    public function getDefaultSettings()
    {
        return array(
            'groups' => false
        );
    }
}
