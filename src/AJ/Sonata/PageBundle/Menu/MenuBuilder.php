<?php

namespace Acme\PageBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
class MenuBuilder
{
    protected $factory;
	protected $em;
	protected $menu = null;
	
	public function createMenuFromMultiArray($tree, $menu,$level,$routeName)
	{
		foreach ($tree as $key => $branch) {
			$label = $branch['title'];
			if(!empty($branch['menuName'])) $label = $branch['menuName'];
			$parameters = array();
			if(isset($branch['route']['routeName'])){
				$parameters['route'] = $branch['route']['routeName'];
			}
			$child = $menu->addChild($label, $parameters);
			$currentLevel= $level+1;
			$hiddenClass = '';
			if($currentLevel){
				$hiddenClass = "hidden";
			}
			$child->setChildrenAttributes(array('class'=>"level_".$currentLevel." ".$hiddenClass));
			if(isset($branch['route']['routeName'])){
				if($branch['route']['routeName'] == $routeName) $child->setCurrent(true);
			}
			if(is_array($branch['__children'])){
				$this->createMenuFromMultiArray($branch['__children'],&$child,$currentLevel,$routeName);
			}
		}
		return $menu;
	}

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, EntityManager $entityManager, SecurityContextInterface  $securityContext)
    {
        $this->factory = $factory;
		$this->em = $entityManager;
		$this->securityContext = $securityContext;
    }

    public function createMainMenu(Request $request, $menuClass)
    {
    	$pageRepo = $this->em->getRepository("AcmePageBundle:Page");
		$routeName = $request->get('_route');

		$menu = $this->factory->createItem('root');
        $queryBuilder = $this->em
            ->createQueryBuilder(array('p', 'mg','r'))
            ->select(array('p','r'))
            ->from('AcmePageBundle:Page', 'p')
			->leftJoin('p.menuGroups', 'mg')
			->leftJoin('p.route', 'r')
			->orderBy('p.root, p.lft', 'ASC')
			 ->where('mg.type = :type')
			 ->setParameter('type', 'main-menu')
			->getQuery(); 
			
		$tree = $pageRepo->buildTree($queryBuilder->getArrayResult());		
		
		$menu->setChildrenAttributes(array('class'=>"level_0 $menuClass"));
		$menu = $this->createMenuFromMultiArray($tree,$menu,0,$routeName);

        return $menu;
    }
	
    public function createFooterMenu(Request $request, $menuClass)
    {
    	$pageRepo = $this->em->getRepository("AcmePageBundle:Page");
		$routeName = $request->get('_route');

		$menu = $this->factory->createItem('root');
        $queryBuilder = $this->em
            ->createQueryBuilder(array('p', 'mg','r'))
            ->select(array('p','r'))
            ->from('AcmePageBundle:Page', 'p')
			->leftJoin('p.menuGroups', 'mg')
			->leftJoin('p.route', 'r')
			->orderBy('p.root, p.lft', 'ASC')
			 ->where('mg.type = :type')
			 ->setParameter('type', 'footer-menu')
			->getQuery(); 
		$tree = $pageRepo->buildTree($queryBuilder->getArrayResult());		
		
		$menu->setChildrenAttributes(array('class'=>"level_0 $menuClass"));
		$menu = $this->createMenuFromMultiArray($tree,$menu,0,$routeName);

        return $menu;
    }
	public function createBreadcrumbMenu(Request $request)
	{
		$pageRepo = $this->em->getRepository("AcmePageBundle:Page");
		$routeRepo = $this->em->getRepository("AcmePageBundle:Route");
		$routeName = $request->get('_route');
		$menu = $this->factory->createItem('root');
		$menu->setChildrenAttributes(array('class'=>"list-breadcrumb"));
		$menu->addChild('Strona główna', array('route' => '_welcome'));
		
		$route = $routeRepo->findOneByRouteName($routeName);
		$current = $pageRepo->findOneByRoute($route);
		
		$current =false;
		if($current){
			$path = $pageRepo->getPath($current);		
			foreach ($path as $key => $step) {
				$menuName = $step->getMenuName();
				$title = $step->getTitle();
				if(empty($menuName)){
					$label = $title;
				}else{
					$label = $menuName;
				}
				$currentCrumb = $menu->addChild($label, array('route' => $step->getRouteName()));
			}
			$currentCrumb->setCurrent(true);
		}		
		return $menu;
	}
	public function createAppMenu(Request $request)
	{
		$routeName = $request->get('_route');
		$menu = $this->factory->createItem('root');
		$menu->setChildrenAttributes(array('class'=>"list-breadcrumb app-menu"));

		$sonata_admin_dashboard=$menu->addChild('Panel cms', array('route' => 'sonata_admin_dashboard'))
									 ->setAttribute("id", "sonata_admin_dashboard_bookmark");

		return $menu;
	}	
}