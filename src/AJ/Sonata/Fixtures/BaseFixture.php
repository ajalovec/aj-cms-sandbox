<?php

namespace AJ\Sonata\Fixtures;

/*
 * This file is part of the AJSonata package.
 *
 * (c) Andraž Jalovec <andraz.jalovec@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


abstract class BaseFixture extends AbstractFixture implements ContainerAwareInterface
{
    protected $container;
    protected $defaults = array();

    abstract public function truncate();
    abstract public function create(ObjectManager $manager);
    
    final public function load(ObjectManager $manager)
    {
        $this->truncate();
        $this->create($manager);
    }

    final public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    final protected function truncateEntity($className = null)
    {
        if(null === $className) {
            return;
        }

        $em     = $this->getEntityManager();
        $cmd    = $em->getClassMetadata($className);
        $db     = $em->getConnection();

        $db->query('SET FOREIGN_KEY_CHECKS=0');

        $db->executeUpdate(
            $db->getDatabasePlatform()->getTruncateTableSql($cmd->getTableName())
        );

        $db->query('SET FOREIGN_KEY_CHECKS=1');
    }


    static protected function populateEntity($entity, array $data = array())
    {
        foreach ($data as $key => $value) {
            $entity->{"set".ucfirst($key)}($value);
        }
    }

    static protected function extractKey(& $array, $key, $var = null)
    {
        if(isset($array[$key])) {
            $var = $array[$key];
            unset($array[$key]);
        }

        return $var;
    }

    static protected function splitNamespace($string, $limit = 1, $delimiter = null)
    {
        $delimiter = $delimiter ?: static::NAMESPACE_DELIMITER;
        $explode = function($_delimiter, $_string, $_limit = null) {
            return (null === $_limit ? explode($_delimiter, $_string) : explode($_delimiter, $_string, ++$_limit));
        };

        if((int) $limit < 0) {
            return (array_map('strrev', $explode($delimiter, strrev($string), abs($limit))));
        }

        return $explode($delimiter, $string, $limit);
    }

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Sonata\PageBundle\Model\SiteManagerInterface
     */
    public function getSiteManager()
    {
        return $this->container->get('sonata.page.manager.site');
    }

    /**
     * @return \Sonata\PageBundle\Model\PageManagerInterface
     */
    public function getPageManager()
    {
        return $this->container->get('sonata.page.manager.page');
    }

    /**
     * @return \Sonata\BlockBundle\Model\BlockManagerInterface
     */
    public function getBlockManager()
    {
        return $this->container->get('sonata.page.manager.block');
    }

    /**
     * @return \Sonata\PageBundle\Entity\BlockInteractor
     */
    public function getBlockInteractor()
    {
        return $this->container->get('sonata.page.block_interactor');
    }


    /**
     * @return \Sonata\MediaBundle\Model\MediaManagerInterface
     */
    public function getMediaManager()
    {
        return $this->container->get('sonata.media.manager.media');
    }

    /**
     * @return \Sonata\MediaBundle\Model\MediaManagerInterface
     */
    public function getGalleryManager()
    {
        return $this->container->get('sonata.media.manager.gallery');
    }

}