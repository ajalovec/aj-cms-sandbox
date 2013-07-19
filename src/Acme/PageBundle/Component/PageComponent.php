<?php
namespace Acme\PageBundle\Component;
use Sonata\SeoBundle\Seo\SeoPage;
use Doctrine\ORM\EntityManager;
use Acme\PageBundle\Component\ModulesSorterComponent;
use Symfony\Component\DependencyInjection\Container;
use Acme\PageBundle\Component\ModulesForwardComponent;
use Acme\PageBundle\Entity\PageModule;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Util\Debug as Debug;
/**
 * PageComponent 
 */
class PageComponent {
	
	protected $title;
	protected $body;
	protected $sonataSeoPage;
	protected $em;
	protected $modules;
	protected $frontRouteId;
	protected $defaultFrontPageFlag;
	protected $container;
	protected $request;
	
	function __construct(EntityManager $entityManager, SeoPage $sonataSeoPage, Container $container) 
	{
		$this->em = $entityManager;
		$this->sonataSeoPage = $sonataSeoPage;
		$this->frontRouteId = null;
		$this->defaultFrontPageFlag = false;
		$this->container = $container;
		$this->request = $this->container->get('request');
	}
	
	public function getPageInstance($routeId = null)
	{
		if($routeId === null){
			throw new \Exception('The page not exist');
		}else{
			$pageRepo = $this->em->getRepository('AcmePageBundle:Page');
			$routeRepo = $this->em->getRepository('AcmePageBundle:Route');
			$moduleRepo = $this->em->getRepository("AcmePageBundle:PageModule");
			
			$route = $routeRepo->find($routeId);
			$page = $pageRepo->findOneByRoute($route);
			
			if(!$route){
				throw new \Exception('The route not exist');
			}
			if(!$page){
				
				throw new \Exception('The page not exist in repo');
			}
			
			if($this->defaultFrontPageFlag){
				$page->addPageModule($this->getFrontPageModule());
			}
			//Usuniecie modulu startowego dla stron ktore nie sa startowe
			if($this->request->get('_route')!='_welcome'){
				$page->removePageModule($this->getFrontPageModule());
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
			
			$this->title = $page->getTitle();
			$this->body = $page->getBody();
			$this->modules = ModulesSorterComponent::modulesSort($page->getPageModules());
			
		}
	}
	
	public function getFrontRouteId()
	{
		$pageModuleRepo = $this->em->getRepository('AcmePageBundle:PageModule');
		$pageRepo = $this->em->getRepository('AcmePageBundle:Page');

		$frontPageModule = $pageModuleRepo->findOneByBlockSubId('front-page');
		if($frontPageModule instanceof PageModule){
			$frontPages = $frontPageModule->getPages();
			if($frontPages instanceof PersistentCollection){
				if($frontPages->first()){
					//$frontRouteId = $frontPages->first()->getId();
					$frontPage = $frontPages->first();
					if($frontPage->getRoute()){
						$frontRouteId = $frontPage->getRoute()->getId();
						return $frontRouteId;
					}else{
						return $this->getDefaultFrontRouteId();
					}
				}else{
					return $this->getDefaultFrontRouteId();
				}
			}
		}
		return $this->getDefaultfrontRouteId();
	}
	
	public function getDefaultFrontRouteId()
	{
		$pageRepo = $this->em->getRepository('AcmePageBundle:Page');
		$routeRepo = $this->em->getRepository('AcmePageBundle:Route');
		$allPages = $pageRepo->findAll();
		$i = 0;
		if(is_array($allPages) && count($allPages)>0){
			foreach ($allPages as $key => $page) {
				if($i>0){
					break;
				}
				if($page->getRoute()){
					$frontRouteId = $page->getRoute()->getId();
					$this->frontRouteId = $frontRouteId;
					$this->defaultFrontPageFlag = true;
					return $frontRouteId;
				}else{
					$frountRoute = $routeRepo->first();
					$frontRouteId = $frountRoute->getId();
					return $frontRouteId;
				}
				$i++;
			}				
		}else{
			throw new \Exception('Stwórz przynajmniej jedna podstronę');
		}
		return null;
	}
	
	public function getFrontPageModule()
	{
		$pageModuleRepo = $this->em->getRepository('AcmePageBundle:PageModule');
		$frontPageModule = $pageModuleRepo->findOneByBlockSubId('front-page');
		if($frontPageModule instanceof PageModule){
			return $frontPageModule;
		}else{
			throw new \Exception('W bazie brak jest modulu startowego');
		}
	}
	
	public function getForward()
	{	
		return ModulesForwardComponent::getForward($this->modules);
		
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function getPageModules()
	{
		return $this->modules;
	}
}
