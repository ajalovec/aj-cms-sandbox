<?php
namespace Acme\PageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Doctrine\ORM\EntityManager;

abstract class BasePageAdmin extends Admin
{
	protected $translationDomain = 'ApplicationSonataAdminBundle';
	
	protected $em;
	
	protected $repositoryName;
	protected $defaultController;

 
    public function __construct($code, $class, $baseControllerName, EntityManager $entityManager, $repositoryName = 'AcmePageBundle:Page', $defaultController = "AcmePageBundle:FrontEndPage:pageView")
    {
		parent::__construct($code, $class, $baseControllerName);
		$this->em = $entityManager;
		$this->repositoryName = $repositoryName;
		$this->defaultController = $defaultController;
    }	

	public function setPageDefaults($object)
	{
		$object->setSlug($object->getFieldForSlug());
		$object->getRoute()->setRouteName("cms_route_".uniqid());
		if(!$object->getRoute()->getController()){
			$object->getRoute()->setController($this->defaultController);	
		}
		return $object;
	}	
	
	public function afterSaveAction($object)
	{
		if($object->getRoute()->getIsRouteStatic()){
			
		}else{
				$object->getRoute()->setPattern($object->getSlug());				
		}
		$pattern = $object->getRoute()->getPattern();
		if(substr($pattern,0,1)!="/"){
			$pattern="/".$pattern;
		}
		$object->getRoute()->setPattern($pattern);	
		$this->em->persist($object);
		$this->em->flush();	
	}
	
    public function update($object)
    {

		$object = $this->setPageDefaults($object);
		if($object->getRoute()){
			$object->getRoute()->setRouteName("cms_route_".$object->getRoute()->getId());
		}
		parent::update($object);
		$this->afterSaveAction($object);
    }

    /**
     * {@inheritdoc}
     */
    public function create($object)
    {
		$object = $this->setPageDefaults($object);
		parent::create($object);
		$this->afterSaveAction($object);	
		
    }  
}
