<?php
namespace Acme\TreeBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
//use Sonata\AdminBundle\Datagrid\ORM\ProxyQuery;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class TreeRepository extends NestedTreeRepository
{
	public function getQueryOrderByRoot($repository)
	{
		$em = $this->getEntityManager();
		
        $queryBuilder = $em
            ->createQueryBuilder('p')
            ->select('p')
            ->from($repository, 'p')
			->orderBy('p.root, p.lft', 'ASC');
			
		$proxyQuery = new ProxyQuery($queryBuilder);
		return $proxyQuery;
	}
	public function getCustomPath($node)
	{
		$pathNodesArr = $this->getPath($node);
		$pathArr = array();
		
		foreach ($pathNodesArr as $key => $currentNode) {
			$pathArr[$key]=$currentNode->getSlug();
		}
		return implode("/", $pathArr);		
	}	
}