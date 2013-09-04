<?php

namespace Acme\ContentBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\Collections\Criteria;


class ContentRepository extends EntityRepository
{
	
	function getMenuTree()
	{
		return "heloo tree";
	}
}