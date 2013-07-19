<?php

namespace Acme\AssortmentBundle\Menu;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Acme\PageBundle\Menu\MenuBuilder as BaseMenuBuilder;

class AssortmentCategoryMenuBuilder extends BaseMenuBuilder
{
    public function createAssortmentCategoryMenu(Request $request, $menuClass)
    {
    	$assortmentCategoryRepo = $this->em->getRepository("AcmeAssortmentBundle:AssortmentCategory");
		$routeName = $request->get('_route');

		$routeRepo = $this->em->getRepository("AcmePageBundle:Route");
		if(strlen($routeName)>0){
			$route = $routeRepo->findOneByRouteName($routeName);
			if($route){
				$assortmentPageRepo = $this->em->getRepository("AcmeAssortmentBundle:AssortmentPage");
				$assortmentPage = $assortmentPageRepo->findOneByRoute($route);
				if($assortmentPage){
					$assortmentCategory = $assortmentPage->getAssortmentCategory();
					if($assortmentCategory){
						$routeName = $assortmentCategory->getRoute()->getRouteName();
					}
				}
			}

		}
		
		$menu = $this->factory->createItem('root');
        $queryBuilder = $this->em
            ->createQueryBuilder(array('ac','r'))
            ->select(array('ac','r'))
            ->from('AcmeAssortmentBundle:AssortmentCategory', 'ac')
			//->leftJoin('p.menuGroups', 'mg')
			->leftJoin('ac.route', 'r')
			->orderBy('ac.root, ac.lft', 'ASC')
			 //->where('mg.type = :type')
			 //->setParameter('type', 'main-menu')
			->getQuery(); 
			
		$tree = $assortmentCategoryRepo->buildTree($queryBuilder->getArrayResult());		
		
		$menu->setChildrenAttributes(array('class'=>"level_0 $menuClass"));
		$menu = $this->createMenuFromMultiArray($tree,$menu,0,$routeName);

        return $menu;
    }
}