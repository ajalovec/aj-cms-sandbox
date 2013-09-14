<?php

namespace Acme\ServicesBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\Collections\Criteria;


class ServicesRepository extends EntityRepository
{
	
	function getMenuTree()
	{
		return "heloo tree";
	}
}