<?php
namespace Acme\PageBundle\Entity;
/**
 * Klasa zawierajaca pomocnicze metody dla podstron
 */
abstract class BasePage {
	
	public function getRoutePattern()
	{
		try{
			if($this->getRoute()){
				$route = $this->getRoute();
				if($route->getPattern()){
					return $route->getPattern();
				}
			}
		}catch (\Exception $e){
			return null;
		}
		return null;
	}
}
