<?php
namespace Acme\AssortmentBundle\Component;
use Acme\PageBundle\Component\PageComponent as BasePageComponent;

/**
 * AssortmentPageComponent 
 */
class AssortmentPageComponent extends BasePageComponent
{
	
	protected $assortmentPage;
	
	public function getAssortmentPageInstance($routeId = null)
	{
		if($routeId === null){
			throw new \Exception('The assortment page route not exist');
		}else{
			$assortmentPageRepo = $this->em->getRepository('AcmeAssortmentBundle:AssortmentPage');
			$routeRepo = $this->em->getRepository('AcmePageBundle:Route');
			
			$route = $routeRepo->find($routeId);
			$assortmentPage = $assortmentPageRepo->findOneByRoute($route);
			
			if(!$route){
				throw new \Exception('The route not exist');
			}
			if(!$assortmentPage){
				
				throw new \Exception('The assortmentPage not exist in repo');
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
			
			$this->assortmentPage = $assortmentPage;
			$this->title = $assortmentPage->getTitle();
			
		}
	}

	public function getAssortmentPage()
	{
		return $this->assortmentPage;
	}
}
