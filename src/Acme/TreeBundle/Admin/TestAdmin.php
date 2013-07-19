<?php
namespace Acme\TreeBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
 
class TestAdmin extends Admin
{
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name')
      ->add('price')
      ->add('description', null, array('required' => false))
	  //->add('laveled_title', null, array('required' => false))
    ;
  }
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('name')
      ->add('price', null, array(
		    'data' => 'abcdef',
		));
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      //->addIdentifier('name')
      ->addIdentifier('laveled_title', null, array('sortable'=>false, 'label'=>'leaveled title	'))	 
	  ->add('kokon', null, array('sortable'=>false, 'label'=>'kokon'))	   
      ->add('price', null, array(
		    'data' => 'abcdef',
		));
    ;
  }
 
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      ->with('name')
      ->assertMaxLength(array('limit' => 32))
      ->end()
    ;
  }
}