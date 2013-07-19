<?php
/*
	Zwykły blok z trescia
*/
namespace Acme\ContentBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Doctrine\Common\Util\Debug;
class ContentBlockService extends BaseBlockService
{
	protected $name;
	protected $templating;
	protected $em;
	
    public function __construct($name, EngineInterface $templating, EntityManager $entityManager)
    {
        $this->name       = $name;
        $this->templating = $templating;
		$this->em = $entityManager;
    }
	
    public function execute(BlockInterface $block, Response $response = null)
    {
		$contentRepo = $this->em->getRepository("AcmeContentBundle:Content");
    	$settings = array_merge($this->getDefaultSettings(), $block->getSettings());
		$content = null;
		$contentSource = null;
		
		if(isset($settings['contentId'])){
			if($settings['contentId']){
				$contentSource = 'id';
			}
		}
		if(isset($settings['contentType'])){
			if($settings['contentType']){
				$contentSource = 'type';
			}
		}
		
		switch ($contentSource) {
			case 'id':
					$content = $contentRepo->find($settings['contentId']);
				break;
			case 'type':
					$content = $contentRepo->findOneByType($settings['contentType']);
				break;
			default:
					$content = null;
				break;
		}
		if(!$content){
			$content['body'] = 'Nie odnaleziono tresci dla bloku';
		}
		if(isset($settings['setBlockTitleFromTitle'])){
			if($settings['setBlockTitleFromTitle']){
				$block->setSetting('blockTitle',$content->getTitle());
			}
		}
		if(!$settings['showTitle']){
			$content->setTitle(null);
		}
		if(!$settings['showBody']){
			$content->setBody(null);
		}
		return $this->renderResponse($settings['template'], array(
			'block' => $block,
			'content' => $content
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

    public function getDefaultSettings()
    {
        return array(
            'contentId' => null,
            'contentType' => null,
            'blockTitle' => null,
            'template' => 'AcmeContentBundle:Block:content.html.twig',
            'showTitle' => true,
            'showBody' => true,
            'setBlockTitleFromTitle' =>false
        );
    }
}
