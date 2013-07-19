<?php
namespace Acme\TreeBundle\Admin;
 
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

abstract class TreeAdmin extends BasePageAdmin
{
	protected $translationDomain = 'ApplicationSonataAdminBundle';
	

 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title')
    ;
  }
 
	public function afterSaveAction($object)
	{
		if($object->getRoute()->getIsRouteStatic()){
			
		}else{
			if($object->getParent()){
	        	$object->getRoute()->setPattern($this->em->getRepository($this->repositoryName)
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
        $query = $this->em->getRepository($this->repositoryName)
            ->getQueryOrderByRoot($this->repositoryName);
		
		return $query;
    }

}