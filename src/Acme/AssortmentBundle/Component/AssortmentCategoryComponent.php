<?php
namespace Acme\AssortmentBundle\Component;
use Acme\PageBundle\Component\PageComponent as BasePageComponent;
/**
 * 
 */
class AssortmentCategoryComponent extends BasePageComponent {
	
	protected $assortmentCategory;
	
	public function getAssortmentCategoryInstance($routeId = null)
	{
		if($routeId === null){
			throw new \Exception('The assortment category route not exist');
		}else{
			$assortmentCategoryRepo = $this->em->getRepository('AcmeAssortmentBundle:AssortmentCategory');
			$routeRepo = $this->em->getRepository('AcmePageBundle:Route');
			
			$route = $routeRepo->find($routeId);
			$assortmentCategory = $assortmentCategoryRepo->findOneByRoute($route);
			
			if(!$route){
				throw new \Exception('The route not exist');
			}
			if(!$assortmentCategory){
				
				throw new \Exception('The assortmentCategory not exist in repo');
			}
			
			$seoPage = $this->sonataSeoPage;
			if($route){
				$seoTitle = trim((string)$route->getSeoTitle());
				if(strlen($seoTitle)>0){
					$seoPage->setTitle($seoTitle);
				}
				$seoDescription = trim((string)$route->getSeoDescription());
				if(strlen($seoDescription)>0){
					$seoPage->addMeta('name', 'description', $seoDescription);
				}
				$seoKeyWords = trim((string)$route->getSeoKeyWords());
				if(strlen($seoKeyWords)>0){
					$seoPage->addMeta('name', 'description', $seoKeyWords);
				}
			}
			
			$this->assortmentCategory = $assortmentCategory;
			$this->title = $assortmentCategory->getTitle();
			//$this->body = $assortmentCategory->getBody();
			
		}
	}

	public function getAssortmentCategory()
	{
		return $this->assortmentCategory;
	}

}
