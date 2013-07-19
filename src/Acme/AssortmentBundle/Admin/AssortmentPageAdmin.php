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
use Acme\PageBundle\Admin\BasePageAdmin;
use Doctrine\ORM\PersistentCollection;

class AssortmentPageAdmin extends BasePageAdmin
{

  protected function configureFormFields(FormMapper $formMapper)
  {
  	$subject = $this->getSubject();
    $id = $subject->getId();  	
	
    $formMapper
    ->with('Categoria')
		->add('title', null, array('label'  => $this->trans('Title')))
		->add('menuName', null, array('required' => false,'label'  => $this->trans('Menu name')))	 
		->add('body', null, array('required' => false,'attr'=> array('class'=>'tinymce')))
		->add('assortmentCategory')		
     	//->add('image', 'sonata_type_model_list'/*, array(), array('link_parameters' => array('context' => 'news'))*/)
        ->add('assortmentPageHasMedias', 'sonata_type_collection', array('label'  => $this->trans('Photos'),
                'cascade_validation' => true,
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
                //'link_parameters' => array('context' => $context),
                'admin_code' => 'acme_assortment.admin.page_has_admin'
            )
        )
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
      ->addIdentifier('title', null, array('sortable'=>false, 'label'=>'Title'))
	  ->add('assortmentPageHasMedias', 'string', array('template' => 'AcmeAssortmentBundle:AssortmentPageAdmin:list_custom.html.twig','label'  => $this->trans('Thumbinal')))
	  ->add('assortmentCategory', 'string', array('template' => 'AcmeAssortmentBundle:AssortmentPageAdmin:list_category.html.twig'))
	  ->add('routePattern', null, array('sortable'=>false, 'label'=>$this->trans('Route')))
	  //->add('custom', 'string', array('template' => 'xcxSonataMediaBundle:MediaAdmin:list_custom.html.twig'))
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
  
	public function afterSaveAction($object)
	{
		if($object->getRoute()->getIsRouteStatic()){
			
		}else{
			if($object->getAssortmentCategory()){
				
				$pattern = $this->em->getRepository('AcmeAssortmentBundle:AssortmentCategory')
            		->getCustomPath($object->getAssortmentCategory());
				$pattern.="/".$object->getSlug();
	        	$object->getRoute()->setPattern($pattern);
			}else{
				$object->getRoute()->setPattern($object->getSlug());				
			}		
		}
		$pattern = $object->getRoute()->getPattern();
		if(substr($pattern,0,1)!="/"){
			$pattern="/".$pattern;
		}
		$object->getRoute()->setPattern($pattern);	
		$this->em->persist($object);
		$this->em->flush();	
	}
  
    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
    	

		
        //$parameters = $this->getPersistentParameters();

       // $gallery->setContext($parameters['context']);

        // fix weird bug with setter object not being call
        $object->setAssortmentPageHasMedias($object->getAssortmentPageHasMedias());
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        // fix weird bug with setter object not being call
        $object->setAssortmentPageHasMedias($object->getAssortmentPageHasMedias());
    }
	
    public function getTemplate($name)
    {
    	switch ($name) {
			case 'edit':
				return 'AcmeAssortmentBundle:CRUD:edit.html.twig';
				break;
		}	
		return parent::getTemplate($name);
    }
}