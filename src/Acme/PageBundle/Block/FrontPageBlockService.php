<?php
/*
	Blok google maps
*/
namespace Acme\PageBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

class FrontPageBlockService extends BaseBlockService
{	
    public function execute(BlockInterface $block, Response $response = null)
    {
		return $this->renderResponse('AcmePageBundle:Block:front.page.html.twig', array(
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
    public function getJavascripts($media)
    {
        return array('/js/front-page.js');
    }
    public function getCss($media)
    {
        return array('/css/front-page.css');
    }

    public function getDefaultSettings()
    {
        return array(
            'groups' => false
        );
    }
}
