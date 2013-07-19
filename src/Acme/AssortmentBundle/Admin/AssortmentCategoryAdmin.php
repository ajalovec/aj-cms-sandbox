<?php
namespace Acme\AssortmentBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Doctrine\Common\Util\Debug as Debug;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\PersistentCollection;
use Acme\TreeBundle\Admin\TreeAdmin as BaseTreeAdmin;

class AssortmentCategoryAdmin extends BaseTreeAdmin
{

  protected function configureFormFields(FormMapper $formMapper)
  {
  	$subject = $this->getSubject();
    $id = $subject->getId();  	
    $formMapper
    ->with('Assortment category')
      ->add('title', null, array('label'  => $this->trans('Title')))
      ->add('menuName', null, array('required' => false,'label'  => $this->trans('Menu name')))	 
		->add('parent', null, array('label' =>  $this->trans('Parent')
		                      , 'required'=>false
		                      , 'query_builder' => function($er) use ($id) {
                                $qb = $er->createQueryBuilder('p');
                                if ($id){
                                    $qb
                                        ->where('p.id <> :id')
                                        ->setParameter('id', $id);
                                }
                                $qb
                                    ->orderBy('p.root, p.lft', 'ASC');
                                return $qb;
                            }
		))		
	->end()
	->with('SEO', array('collapsed' => true))
	  ->add('route', 'route_seo')
	->end()
    ;
  }
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title')
//      ->add('route')
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('leveled_title', null, array('sortable'=>false, 'label'=>'Title'))
	  ->add('routePattern', null, array('sortable'=>false, 'label'=>$this->trans('Route')))
//      ->add('route')
    ;
  }
 
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
	->with('title')
		->assertNotNull(array())
		->assertNotBlank()	  
		->assertMaxLength(array('limit' => 1000))
	->end();
  }

}