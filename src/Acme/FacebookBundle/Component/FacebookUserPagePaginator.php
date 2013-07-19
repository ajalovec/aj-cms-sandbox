<?php
namespace Acme\FacebookBundle\Component;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Util\Debug;
use Acme\AssortmentBundle\Entity\AssortmentCategory;
/**
 * Komponent zapewniajacy paginacje podstron asortymentu
 */
class FacebookUserPagePaginator{
	
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
	
	public function getPagination()
	{
		$facebookUsersRepo = $this->em->getRepository('AcmeFacebookBundle:FacebookUser');
		
        $query = $this->em
            ->createQueryBuilder(array('fb'))
            ->select(array('fb'))
            ->from('AcmeFacebookBundle:FacebookUser', 'fb');

	    $pagination = $this->knp_paginator->paginate(
	        $query,
	        $this->request->query->get('page', 1)/*page number*/,
	        $this->pageLimit/*limit per page*/
	    );
		return $pagination;
	}
}
