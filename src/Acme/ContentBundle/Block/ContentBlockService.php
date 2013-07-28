<?php
/*
	Zwykły blok z trescia
*/
namespace Acme\ContentBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Util\Debug;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\AdminInterface;


class ContentBlockService extends BaseBlockService
{
	protected $em;
	
	public function __construct($name, EngineInterface $templating, EntityManager $entityManager)
    {
        parent::__construct($name, $templating);
		$this->em 			= $entityManager;
    }
	
	/**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Content';
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title' => "",
            'showTitle'=> true,
            'contentId' => false,
            'content' => false,
            'template' 	=> 'AcmeContentBundle:Block:content.html.twig',
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
    	$manager = $this->em->getRepository("AcmeContentBundle:Content");

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title', 'text', array('required' => false)),
        //        array($contentForm, null, array()),
                array('contentId', 'choice', array(
				    'choices'   => function (Options $opts, $previousValue) use ($manager) {
    					$items = $manager->findAll();
				    	$list = array();
    					foreach ($items as $i => $content) {
    						$list[$content->getId()] = $content->getTitle();
    					}
		                return $list;
		            },
				    'multiple'  => false,
				    'expanded'  => false,
				    //'translation_domain' => $this->getTranslationDomain()
				))
            )
        ));

    }
//public function execute(BlockContextInterface $blockContext, Response $response = null)
//    {
//        return $this->renderResponse($blockContext->getTemplate(), array(
//            'block'      => $blockContext->getBlock(),
//            'decorator'  => $this->getDecorator($blockContext->getSetting('layout')),
//            'settings'   => $blockContext->getSettings(),
//        ), $response);
//    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
		$content = $this->em->getRepository("AcmeContentBundle:Content")
							->find($blockContext->getSetting('contentId'));
		

		if(! $blockContext->getSetting('showTitle')){
			$content->setTitle(null);
		}
		//$blockContext->setSetting('content', $content);

		return $this->renderResponse($blockContext->getTemplate(), array(
			'block' => $blockContext->getBlock(),
			'settings' => $blockContext->getSettings(),
			'content' => $content
		), $response);
    }
}
