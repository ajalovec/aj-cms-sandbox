<?php
namespace Acme\FacebookBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class FacebookFeedAdmin extends Admin
{
    protected $description;
	protected $translationDomain = 'ApplicationSonataAdminBundle';
	
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('link')
      ->add('picture')
	  ->add('name') 
	  ->add('caption') 
	  ->add('description') 
    ;
  }
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('picture')
	  ->add('name') 
	  ->add('caption')  
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
	  ->addIdentifier('name') 
      ->add('picture')
	  ->add('caption')  
    ;
  }
 
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      //->with('title')
      //->assertMaxLength(array('limit' => 100))
      //->end()
    ;
  }

    public function getTemplate($name)
    {
	    switch ($name) {
	        case 'layout':
				return parent::getTemplate($name);
	            break;
	        default:
	            return parent::getTemplate($name);
	            break;
	    }

        return null;
    }
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
            ;

    }
}