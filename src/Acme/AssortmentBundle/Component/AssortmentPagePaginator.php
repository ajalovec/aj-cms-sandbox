<?php
namespace Acme\AssortmentBundle\Component;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Util\Debug;
use Acme\AssortmentBundle\Entity\AssortmentCategory;
/**
 * Komponent zapewniajacy paginacje podstron asortymentu
 */
class AssortmentPagePaginator{
	
	protected $em;
	protected $request;
	protected $knp_paginator;
	protected $pageLimit;
	
	function __construct(EntityManager $entityManager,Paginator $knp_paginator,$pageLimit) 
	{
		$this->em = $entityManager;
		$this->request = Request::createFromGlobals();
		$this->knp_paginator = $knp_paginator;
		$this->pageLimit = $pageLimit;
	}
	
	public function getPagination(AssortmentCategory $assortmentCategory = null)
	{
		$assortmentCategoryRepo = $this->em->getRepository('AcmeAssortmentBundle:AssortmentCategory');
		
		if($assortmentCategory){
			$assortmentCategoryQueryBuilder = $assortmentCategoryRepo->childrenQueryBuilder($assortmentCategory);
			$assortmentCategoryQueryBuilderWhere = $assortmentCategoryQueryBuilder->getDQLPart('where');
		}	
		// 'node' - assortmentCategory
        $query = $this->em
            ->createQueryBuilder(array('ap','node','r'))
            ->select(array('ap','node','r'))
            ->from('AcmeAssortmentBundle:AssortmentPage', 'ap')
			->leftJoin('ap.assortmentCategory', 'node')
			->leftJoin('ap.route', 'r');
		if($assortmentCategory){
			$assortmentCategoryId = $assortmentCategory->getId();
			$query->where('('.$assortmentCategoryQueryBuilderWhere.') OR node.id = :rootNode');
            $query->setParameter('rootNode', $assortmentCategoryId);
		}	

		//$query->getQuery();

	    $pagination = $this->knp_paginator->paginate(
	        $query,
	        $this->request->query->get('page', 1)/*page number*/,
	        $this->pageLimit/*limit per page*/
	    );
		return $pagination;
	}
}
