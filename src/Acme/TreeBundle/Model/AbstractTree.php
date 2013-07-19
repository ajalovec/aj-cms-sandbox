<?php
namespace Acme\TreeBundle\Model;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Acme\PageBundle\Entity\BasePage;
abstract class AbstractTree extends BasePage
{
 
    //public abstract function setParent($parent);
 
    //public abstract function getChildren();
    
    public function __toString()
    {
    	$lvl = $this->getLvl();
        $prefix = "";
        for ($i=1; $i<= $lvl; $i++){
            $prefix .= "-- ";
        }
        return $prefix . $this->title;
    }

    public function getLeveledTitle()
    {
        return (string)$this;
    }
	
	
	
	
}