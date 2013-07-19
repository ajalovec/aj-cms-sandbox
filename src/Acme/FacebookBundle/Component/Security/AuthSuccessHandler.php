<?php
namespace Acme\FacebookBundle\Component\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Cmf\Component\Routing\ChainRouter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Acme\FacebookBundle\Component\AcmeFacebookComponent;


/**
 * Fos facebook custom Auth Success Handler
 */
class AuthSuccessHandler implements AuthenticationSuccessHandlerInterface {
	
	protected $router;
	protected $route_name;
	protected $acmeAssortmentIntegrator;
	
	public function __construct(ChainRouter $router, $route_name, AcmeFacebookComponent $acmeFacebookComponent)
	{
		$this->router = $router;
		$this->route_name = $route_name;
		$this->acmeFacebookComponent = $acmeFacebookComponent;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
	    //$user = $token->getUser();
		$this->acmeFacebookComponent->saveFacebookUserIntoDB();
	    
	    return new RedirectResponse($this->router->generate($this->route_name));
	}
}
