<?php

// src/Acme/DemoBundle/Twig/AcmeExtension.php
namespace Acme\AssortmentBundle\Twig\Extension;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Util\Debug;

class AssortmentExtension extends \Twig_Extension
{
	protected $em;
	private $environment;

	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}
	
	public function initRuntime(\Twig_Environment $environment)
	{
	    $this->environment = $environment;
	}

	public function getFunctions()
    {
        return array(
            'assortment_top_pages'      => new \Twig_Function_Method($this, 'assortmentTopPages'),
        );
    }

	public function assortmentTopPages()
	{
		$html = '';
		
        $queryBuilder = $this->em->createQueryBuilder(array('ap','r','ac','aphm'));
        $qb = $queryBuilder
            ->select(array('ap','r','ac','aphm'))
            ->from('AcmeAssortmentBundle:AssortmentPage', 'ap')
			->leftJoin('ap.assortmentCategory', 'ac')
			->leftJoin('ap.route', 'r')
			->leftJoin('ap.assortmentPageHasMedias', 'aphm')
			//->where($queryBuilder->expr()->isNotNull('ap.assortmentPageHasMedias'))
			//->andWhere($queryBuilder->expr()->isNotNull('ap.route'))
			->getQuery(); 
			
			
		$result = $qb->getResult();
			
		if(is_array($result)){
			foreach ($result as $key => $value) {
				
				if($result[$key]->getAssortmentPageHasMedias()){
					$assortmentPageHasMedias = $result[$key]->getAssortmentPageHasMedias();
					if(count($assortmentPageHasMedias)<1){
						unset($result[$key]);
					}
				}else{
					unset($result[$key]);
				}
				
				if($result[$key]->getRoute()){
					$route = $result[$key]->getRoute();
					if($route->getRouteName()){
						if(strlen($route->getRouteName())<1){
							unset($result[$key]);
						}
					}else{
						unset($result[$key]);
					}
				}else{
					unset($result[$key]);
				}
			}
		}
		
		$template = $this->environment->loadTemplate('AcmeAssortmentBundle:Twig:assortment_top_pages.html.twig');
		
		if(count($result)>=4){
			echo $template->render(array(
			    'assortment_top_pages' => $result
			  ));
		}else{
			echo "podaj przynajmniej 4ry obiekty";
		}


	}

    public function getName()
    {
        return 'assortment_extension';
    }
}