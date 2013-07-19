<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\FormBundle\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Acme\FormBundle\Entity\Form;
use Doctrine\Common\Util\Debug;
/*
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManager;
*/
use Symfony\Component\DependencyInjection\Container;
/**
 *
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class FormBlockService extends BaseBlockService 
{
    protected $name;
    protected $templating;
	protected $formFactoryInterface;
	protected $translator;
	protected $em;
	protected $container;
	protected $blockServiceManager;
	protected $validFlag;
	protected $type;
	protected $emailComponent;
	
    public function __construct($name, EngineInterface $templating, Container $container, $emailComponent)
    {
        $this->name       = $name;
        $this->templating = $templating;
		$this->container = $container;
		$this->validFlag = FALSE;
		
		$this->formFactoryInterface = $this->container->get('form.factory');
		$this->translator = $this->container->get('translator');
		$this->em = $this->container->get('doctrine.orm.entity_manager');
		$this->emailComponent = $emailComponent;
		//$this->blockServiceManager = $this->container->get('sonata.block.manager');
		//$this->blockServiceManager->add($name, $this);
    }

    public function execute(BlockInterface $block, Response $response = null)
    {
		//$currentUser = $this->container->get('security.context')->getToken()->getUser(); 
		$request = $this->container->get('request');
		$routeName = $request->get('_route');
		$formObj = new Form();
		
		$mainType = $this->container->get('acme_form.main_type');
		$mainForm = $this->formFactoryInterface->create($mainType, $formObj);
		
		$hauseType = $this->container->get('acme_form.hause_type');
		$hauseForm = $this->formFactoryInterface->create($hauseType, $formObj, 
		array('validation_groups' => array('hause_type')));
		
		$flatType = $this->container->get('acme_form.flat_type');
		$flatForm = $this->formFactoryInterface->create($flatType, $formObj, 
		array('validation_groups' => array('flat_type')));
		
		$localType = $this->container->get('acme_form.local_type');
		$localForm = $this->formFactoryInterface->create($localType, $formObj, 
		array('validation_groups' => array('local_type')));	
			
		$parcelType = $this->container->get('acme_form.parcel_type');
		$parcelForm = $this->formFactoryInterface->create($parcelType, $formObj, 
		array('validation_groups' => array('parcel_type')));	
		
		$storageType = $this->container->get('acme_form.storage_type');
		$storageForm = $this->formFactoryInterface->create($storageType, $formObj, 
		array('validation_groups' => array('storage_type')));
		
		$this->type = null;
		$this->validFlag = 'valid';
		$guard = false;
		
		if ($request->isMethod('POST')) {
			//throw new \Exception('Something went wrong!');
			if ($request->request->has('hause')) {
				$this->type = 'hause';
				$guard = $this->saveForm($hauseForm,$request, $formObj);
			}			
			if ($request->request->has('flat')) {
				$this->type = 'flat';
				$guard = $this->saveForm($flatForm,$request, $formObj);
			}
			if ($request->request->has('local')) {
				$this->type = 'local';
				$guard = $this->saveForm($localForm,$request, $formObj);
			}	
			if ($request->request->has('parcel')) {
				$this->type = 'parcel';
				$guard = $this->saveForm($parcelForm,$request, $formObj);
			}	
			if ($request->request->has('storage')) {
				$this->type = 'storage';
				$guard = $this->saveForm($storageForm,$request, $formObj);
			}	
			if($guard){
				$content = "Pomyślnie wysłano formularz.";
				$title = "Gratulacje";		
					
				return $this->renderResponse('AcmeFormBundle::formSuccessView.html.twig', array(
					'block' => $block,
					'content' => $content	
				));						
			}
		}
		 
		return $this->renderResponse('AcmeFormBundle::formView.html.twig', array(
			'block'     => $block,
			'type'     => $this->type,
			'validFlag'     => $this->validFlag,
			'routeName' =>	$routeName,
			'mainForm' => $mainForm->createView(),
			'hauseTypeForm' => $hauseForm->createView(),
			'flatTypeForm' => $flatForm->createView(),
			'localTypeForm' => $localForm->createView(),
			'parcelTypeForm' => $parcelForm->createView(),
			'storageTypeForm' => $storageForm->createView()
		));
    }
	public function saveForm($form,$request,$formObj)
	{
		
		$em = $this->em;
		$form->bind($request);
		if ($form->isValid()) {
			$media = $formObj->getMedia();
			if(is_array($media)){
				$media = implode(',', $media);
				$formObj->setMedia($media);
			}
			$em->persist($formObj);
			$em->flush();	
			$this->validFlag = 'valid';
			
			$recipients = $this->emailComponent->getEmailsByType('form');
			$senders = $this->emailComponent->getEmailsByType('default_sender');
			$this->emailComponent->emailSend($senders,$recipients,"AcmeFormBundle:Email:email.html.twig",$formObj,"Formularz dla poszukujących nieruchomości");
			
			
			return true;
		}else{
			$this->validFlag = 'invalid';
		}	
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

    /**
     * {@inheritdoc}
     */
    /*public function getName()
    {
        return 'acme_form.block.service.form';
    }
    public function getType()
    {
        return 'acme_form.block.service.form';
    }
	 */
    public function getJavascripts($media)
    {
        return array('/js/form.panel.js');
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'groups' => false
        );
    }
}
