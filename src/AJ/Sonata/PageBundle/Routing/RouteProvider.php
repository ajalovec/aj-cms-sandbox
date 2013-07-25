<?php
namespace Acme\PageBundle\Routing;
//use Doctrine\ODM\PHPCR\DocumentRepository;
//use Symfony\Cmf\Component\Routing\RouteRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\Route;

use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Kernel;
use Doctrine\Common\Util\Debug;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
//echo "Uruchomilem rute repository";
class RouteProvider implements RouteProviderInterface/*extends DocumentRepository implements RouteRepositoryInterface*/
{
	protected $em;
	protected $container;
	protected $kernel_environment;
	protected $frontPageUrl ;
	
    /**
     * Finds routes that may potentially match the request.
     *
     * This may return a mixed list of class instances, but all routes returned
     * must extend the core symfony route. The classes may also implement
     * RouteObjectInterface to link to a content document.
     *
     * This method may not throw an exception based on implementation specific
     * restrictions on the url. That case is considered a not found - returning
     * an empty array. Exceptions are only used to abort the whole request in
     * case something is seriously broken, like the storage backend being down.
     *
     * Note that implementations may not implement an optimal matching
     * algorithm, simply a reasonable first pass.  That allows for potentially
     * very large route sets to be filtered down to likely candidates, which
     * may then be filtered in memory more completely.
     *
     * @param Request $request A request against which to match.
     *
     * @return \Symfony\Component\Routing\RouteCollection with all urls that
     *      could potentially match $request. Empty collection if nothing can
     *      match.
     */
    public function __construct(EntityManager $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
		$this->container = $container;
		$this->kernel_environment = $container->get('kernel')->getEnvironment();
		//$this->frontPageUrl =  $this->container->get('router')->generate('_welcome');
    }
	
	public function notFoundedRouteAsFrontPage($url)
	{
		$collection = new RouteCollection();
		$route = new SymfonyRoute($url, 
			array('_controller' => "AcmePageBundle:FrontEndPage:startView")
			);
			$collection->add('_welcome', $route);
		return  $collection;
	}
	
    public function getRouteCollectionForRequest(Request $request){
    	$url = $request->getPathInfo();
		
		/**
		 * Znajdowanie po urlu
		 */
		$dbRouteRepo = $this->em->getRepository("AcmePageBundle:Route");
		$dbRoutes = $dbRouteRepo->findByPattern($url);
		
		if(!$dbRoutes){
			if($this->kernel_environment == "prod"){
				//return $this->notFoundedRouteAsFrontPage($url);
			}
		}
		$collection = new RouteCollection();
		foreach ($dbRoutes as $key => $dbRoute) {
			$name = $dbRoute->getRouteName();
			$route = new SymfonyRoute($dbRoute->getPattern(), 
				array('_controller' => $dbRoute->getController(),
						'routeId' => $dbRoute->getId())
				);
				$collection->add($dbRoute->getRouteName(), $route);
		}
		return  $collection;
    }

    /**
     * Find the route using the provided route name (and parameters)
     *
     * @param string $name the route name to fetch
     * @param array $parameters the parameters as they are passed to the
     *      UrlGeneratorInterface::generate call
     *
     * @return \Symfony\Component\Routing\Route
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException if
     *      there is no route with that name in this repository
     */
    public function getRouteByName($name, $parameters = array()){
		/**
		 * Znajdowanie po nejmie
		 */
		$dbRouteRepo = $this->em->getRepository("AcmePageBundle:Route");
		$dbRoute = $dbRouteRepo->findOneByRouteName($name);
        if (!$dbRoute) {

            throw new RouteNotFoundException("Nie odnaleziono sciezki/strony o nazwie '$name'");
        }		
		
		$route = new SymfonyRoute($dbRoute->getPattern(), 
			array('_controller' => $dbRoute->getController(),
					'routeId' => $dbRoute->getId())
			);		
		return $route;
    }

    /**
     * Find many routes by their names using the provided list of names
     *
     * Note that this method may not throw an exception if some of the routes
     * are not found. It will just return the list of those routes it found.
     *
     * This method exists in order to allow performance optimizations. The
     * simple implementation could be to just repeatedly call
     * $this->getRouteByName()
     *
     * @param array $names the list of names to retrieve
     * @param array $parameters the parameters as they are passed to the
     *      UrlGeneratorInterface::generate call. (Only one array, not one for
     *      each entry in $names.
     *
     * @return \Symfony\Component\Routing\Route[] iterable thing with the keys
     *      the names of the $names argument.
     */
    public function getRoutesByNames($names, $parameters = array()){
		$collection = new RouteCollection();
		$dbRouteRepo = $this->em->getRepository("AcmePageBundle:Route");
		$dbRoutes = $dbRouteRepo->findBy(array('routeName'=>$names));
		foreach ($dbRoutes as $key => $dbRoute) {
			$route = new SymfonyRoute($dbRoute->getPattern(), 
				array('_controller' => $dbRoute->getController(),
						'routeId' => $dbRoute->getId())
				);
				$collection->add($dbRoute->getRouteName(), $route);
		}
		return  $collection;
    }	
	
	
    // this method is used to find routes matching the given URL

}