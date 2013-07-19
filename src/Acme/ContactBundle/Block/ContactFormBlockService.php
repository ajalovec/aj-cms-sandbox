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
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Common\Util\Debug;

class ContactFormBlockService extends BaseBlockService
{
	protected $formFactory;
	protected $contactFormType;
    protected $name;
    protected $templating;
	protected $FormFactoryInterface;
	protected $container;
	protected $emailComponent;

    public function __construct($name, EngineInterface $templating, Container $container)
    {
        $this->name       = $name;
        $this->templating = $templating;
		
    	$this->container = $container;
		$this->emailComponent = $this->container->get('acme_email.email.component');
		$this->contactFormType = $this->container->get('acme_contact.form.type.contact_form_type');
		$this->formFactory = $this->container->get('form.factory');
		$this->request = $this->container->get('request');
    }
		
    public function execute(BlockInterface $block, Response $response = null)
    {
		//$form = $this->formFactory->create($this->contactFormType, $formObj);
    $defaultData = array('name' => '','phone' => '','email' => '','message' => '');
	
		$mainType = $this->contactFormType;//->get('acme_form.main_type');
		$form = $this->formFactory->create($mainType, $defaultData);
		
		   if ($this->request->isMethod('POST')) {
		        $form->bind($this->request);
		
		        if ($form->isValid()) {
					$content = "Pomyślnie wysłano formularz.";
					$title = "Gratulacje";		
					
					///echo "Klasa:".get_class($form);
					
					///echo "<pre>";
					//Debug::dump($form);
					//echo "</pre>";
						
					//die();
					$recipients = $this->emailComponent->getEmailsByType('default_sender');
					$senders = $this->emailComponent->getEmailsByType('default_sender');
					$this->emailComponent->emailSend($senders,$recipients,"AcmeContactBundle:Email:contact.form.html.twig",$form->getData(),"Formularz wysłany ze strony kontaktowej");
						
					return $this->renderResponse('AcmeFormBundle::formSuccessView.html.twig', array(
						'block' => $block,
						'content' => $content	
					));		
		        }
		    }
		
		return $this->renderResponse('AcmeContactBundle:Block:contact.form.html.twig', array(
			'block' => $block,
			'form' => $form->createView()
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
        return array();
    }
    public function getCss($media)
    {
        return array();
    }

    public function getDefaultSettings()
    {
        return array(
            'groups' => false
        );
    }
}
