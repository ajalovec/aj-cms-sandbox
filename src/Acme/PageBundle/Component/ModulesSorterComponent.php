<?php
namespace Acme\PageBundle\Component;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\PersistentCollection;
/**
 * Komponent zmieniajacy koolejnosc blokow w roznych przypadkach
 */
abstract class ModulesSorterComponent  {
	
	public static function modulesSort($modules)
	{
		$modulesOrdered = array();
		if($modules instanceof PersistentCollection){
			if($modules->count()>1){
				foreach ($modules as $key => $module) {
					if($module->getBlockSubId()=='contact-form'){
						unset($modules[$key]);
						$restModules = self::restModules($modules);
						$modulesOrdered = array_merge(array($module), $restModules);
					}
				}
				return $modulesOrdered;
			}
		}
		return $modules;
	}
	
	public static function restModules($modules)
	{
		$restModules = array();
		foreach ($modules as $key => $module) {
			array_push($restModules,$module);			
		}
		return $restModules;
	}
	
}
