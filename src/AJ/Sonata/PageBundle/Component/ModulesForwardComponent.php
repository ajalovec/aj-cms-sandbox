<?php
namespace Acme\PageBundle\Component;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\PersistentCollection;
/**
 * Komponent komponent sprawdzajacy czy nastepuje przekierowanie
 */
abstract class ModulesForwardComponent  {
	
	public static function getForward($modules)
	{
		$modulesOrdered = array();
		if($modules instanceof PersistentCollection){
			foreach ($modules as $key => $module) {
				if($module->getForward() !==null){
					return $module->getForward();
				}
			}
			return null;;
		}
		return null;
	}
	
}
