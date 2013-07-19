<?php
namespace Acme\PageBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Doctrine\Common\Util\Debug as Debug;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

use Acme\PageBundle\Entity\Page;
use Acme\PageBundle\Entity\PageModule;
use Doctrine\ORM\PersistentCollection;

class PageAdmin extends Admin
{
	protected $translationDomain = 'ApplicationSonataAdminBundle';
	
	protected $em;
	
  protected function configureFormFields(FormMapper $formMapper)
  {
  	$subject = $this->getSubject();
    $id = $subject->getId();  	
    $formMapper
    ->with('Podstrona')
      ->add('title', null, array('label'  => $this->trans('Title')))
      ->add('menuName', null, array('required' => false,'label'  => $this->trans('Menu name')))	 
	  ->add('menuGroups', null, array('required' => false,'label'  => $this->trans('Menu groups'), 'multiple' =>true,'expanded'=>true))	 
	  ->add('pageModules', null, array('required' => false,'label'  => $this->trans('Modules'), 'multiple' =>true,'expanded'=>true))	 
		//->add('parent', "sonata_type_model", array('label'  => $this->trans('Parent')))   
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
		   
      ->add('body', null, array('required' => false,'label'  => $this->trans('Body'),'attr'=> array('class'=>'tinymce')))
	   /* ->add('body', 'ckeditor', array(
	        //'transformers'                 => array('strip_js', 'strip_css', 'strip_comments'),
	        //'toolbar'                      => array('document','basicstyles'),
	        //'toolbar_groups'               => array(
	        //    'document' => array('Source')
	       // ),
	        //'ui_color'                     => '#fff',
	        //'startup_outline_blocks'       => false,
	        'width'                        => '100%',
	        'height'                       => '320',
	        'language'                     => 'en-au',
	        'filebrowser_image_browse_url' => array(
	            'url' => 'relative-url.php?type=file',
	        ),
	        'filebrowser_image_browse_url' => array(
	            'route'            => 'route_name',
	            'route_parameters' => array(
	                'type' => 'image',
	            ),
	        ),
	    ))*/	  
        /*->add('body', 'ckeditor', array(
            'transformers' => array(),
            'width'=> '426',
	        'filebrowser_image_browse_url' => array(
	            'url' => '/kupno?type=file',
	        ),
	        'filebrowser_image_browse_url' => array(
	            'route'            => 'route_name',
	            'route_parameters' => array(
	                'type' => 'image',
	            ),
	        ),            
        ))*/
	->end()
	->with('SEO', array('collapsed' => true))
	  /*->add('seoTitle', null, array('required' => false,'label'  => $this->trans('Seo title')))	  	  
	  ->add('seoDescription', null, array('required' => false,'label'  => $this->trans('Seo description')))
	  ->add('seoKeyWords', null, array('required' => false,'label'  => $this->trans('Seo key words')))
	  ->add('isRouteStatic')
	  ->add('pattern', null, array('required' => false,'label'  => $this->trans('Route')))	
	  ->add('pattern', 'entity', array('required' => false,'label'  => $this->trans('Route')))*/
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
  
    public function update($object)
    {
    	$this->removeAllFrontPageModules($object);
		$object = $this->setPageDefaults($object);
		$object->getRoute()->setRouteName("cms_page_".$object->getId());
		parent::update($object);
		$this->afterSaveAction($object);
    }

    /**
     * {@inheritdoc}
     */
    public function create($object)
    {
    	$this->removeAllFrontPageModules($object);
		$object = $this->setPageDefaults($object);
		parent::create($object);
		$this->afterSaveAction($object);	
    }  
    public function __construct($code, $class, $baseControllerName, EntityManager $entityManager)
    {
		parent::__construct($code, $class, $baseControllerName);
		$this->em = $entityManager;
    }	
	/*
	 * 
	 */
    public function getTemplate($name)
    {
		switch ($name) {
			case 'edit':
				return 'AcmePageBundle:CRUD:edit.html.twig';
				break;
			
			default:
				
				break;
		}
		//base_edit_form.html.twig
        if (isset($this->templates[$name])) {
            return $this->templates[$name];
        }

        return null;
    }
    public function reorderFormGroup($group, array $keys)
    {
        $formGroups = $this->getFormGroups();
		Debug::dump($formGroups);
        $formGroups[$group]['fields'] = array_merge(array_flip($keys), $formGroups[$group]['fields']);
        $this->setFormGroups($formGroups);
    }
	
	public function getHiddensBool($hiddenGroupName)
	{
		$sonataBaCollapsedHidden = $this->request->request->get('sonata-ba-collapsed-hidden');
		if(is_array($sonataBaCollapsedHidden)){
			foreach ($sonataBaCollapsedHidden as $groupName => $value) {
				if($value=="enabled"){
					if($hiddenGroupName == $groupName){
						return true;
					}
				}
			}
		}
		return false;	
	}	
	public function setPageDefaults($object)
	{
		$object->setSlug($object->getFieldForSlug());
		$object->getRoute()->setRouteName("cms_page_".uniqid());
		if(!$object->getRoute()->getController()){
			$object->getRoute()->setController("AcmePageBundle:FrontEndPage:pageView");	
		}
		return $object;
	}	
	public function afterSaveAction($object)
	{
		if($object->getRoute()->getIsRouteStatic()){
			
		}else{
			if($object->getParent()){
	        	$object->getRoute()->setPattern($this->em->getRepository('AcmePageBundle:Page')
            		->getCustomPath($object));
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
	
    public function createQuery($context = 'list')
    {
        $query = $this->em->getRepository('AcmePageBundle:Page')
            ->getQueryOrderByRoot('AcmePageBundle:Page');
		
		return $query;
    }
	public function getNewInstance()
	{
	    $instance = parent::getNewInstance();
		$defaultMenuGroup = $this->em->getRepository('AcmePageBundle:MenuGroup')->find(1);
	    $instance->addMenuGroup($defaultMenuGroup);
		//$defaultPageModule = $this->em->getRepository('AcmePageBundle:PageModule')->find(1);
	    //$instance->addPageModule($defaultPageModule);	    	
	
	    return $instance;
	}	
	
	public function removeAllFrontPageModules($object)
	{
        if ($object instanceof Page) {
        	$page = $object;
			$pageModules = $page->getPageModules();
			
			if ($pageModules instanceof PersistentCollection) {
				foreach ($pageModules as $pageModuleKey => $pageModule) {
					if($pageModule->getBlockSubId() == 'front-page'){
						$frontPages = $this->getAllFrontPages();
						if($frontPages !== null){
							foreach ($frontPages as $frontPageKey => $frontPage) {
								if($frontPage != $page){
									$frontPageModules = $frontPage->getPageModules();
									if($frontPageModules instanceof PersistentCollection){
										foreach ($frontPageModules as $frontPageModuleKey => $frontPageModule) {
											if($frontPageModule->getBlockSubId() == 'front-page'){
												$frontPage->removePageModule($frontPageModule);
												$this->em->persist($frontPage);
											}
										}
									}
								}
							}
							$this->em->flush();
						}
						
					}
				}
			}
        }
	}
	public function getAllFrontPages(){
		$pageModuleRepo = $this->em->getRepository('AcmePageBundle:PageModule');
		$frontPageModule = $pageModuleRepo->findOneByBlockSubId('front-page');
		if($frontPageModule instanceof PageModule){
			$frontPages = $frontPageModule->getPages();
			if($frontPages instanceof PersistentCollection){
				return $frontPages;
			}
		}
		return null;
	}
	
    public function getFormGroups()
    {
    	/*echo "<br><br><br><br> Get form groups";
		echo "<pre>";
		print_r(parent::getFormGroups());
		echo "</pre>";*/
        return parent::getFormGroups();
    }
}