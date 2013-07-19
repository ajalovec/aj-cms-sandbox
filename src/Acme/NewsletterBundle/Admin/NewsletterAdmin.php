<?php
// src/Tutorial/BlogBundle/Admin/PostAdmin.php
namespace Acme\NewsletterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterAdmin extends Admin
{

	protected $translationDomain = 'ApplicationSonataAdminBundle';

  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('subject',null,array('label' => 'Newsletter subject', 'required' => true))
	  ->add('body', null, array('required' => true,'label'  => $this->trans('Body'),'attr'=> array('class'=>'tinymce')))
      //->add('name')
      //->add('senderMail','email', array('required' => true))
	  //->add('senderName', null, array('required' => true))
	  //->add('returnMail','email')
  
	  //->add('laveled_title', null, array('required' => false))
    ;
  }
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('subject')
      //->add('senderMail')
      //->add('senderName')
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
	  ->addIdentifier('subject',null,array('label' => 'Newsletter subject', 'required' => true))
      //->add('senderMail')
	  //->add('senderName')
    ;
  }
 
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
	;
  }
  
	public function getTemplate($name)
	{
	    switch ($name) {
	        case 'edit':
	            return 'AcmeNewsletterBundle:CRUD:edit.html.twig';
	            break;
	        default:
	            return parent::getTemplate($name);
	            break;
	    }
	}
	protected function configureRoutes(RouteCollection $collection) {
		$collection->add('admin_newsletter_send',$this->getRouterIdParameter().'/newsletter/send',array("objectId"=>$this->getRouterIdParameter())); 
	}
	

}