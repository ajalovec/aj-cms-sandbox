<?php
namespace Acme\ContentBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ContentAdmin extends Admin
{
	
	protected $translationDomain = 'ApplicationSonataAdminBundle';
	
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('title', null, array('required' => false, 'label' => $this->trans('Title'), 'attr'=> array()))
      ->add('description', null, array('required' => false, 'label' => $this->trans('Description').' (pomocniczy, nie wyświetla się na stronie)', 'attr'=> array()))
	  ->add('body', null, array('label' => $this->trans('Body'), 'attr' => array('class'=>'tinymce'))) 
    ;
  }
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title', null, array('label' => $this->trans('Title')))
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('title', null, array('label' => $this->trans('Title')))
      ->add('description', null,  array('label' => $this->trans('Description')))
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
				$id = $this->request->get($this->getIdParameter());
				$object = $this->getObject($id);
				if($object){
					if($object->getAbsolutePathFlag()){
						return 'AcmeContentBundle::layout.html.twig';
					}
				}
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