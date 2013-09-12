<?php

namespace AJ\Sonata\Fixtures;

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\PageInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml as YamlParser;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;


abstract class BaseFixture extends AbstractFixture implements ContainerAwareInterface
{
    protected $container;
    protected $defaults = array();

    

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    protected function truncateEntity($className = null)
    {
        $em     = $this->getEntityManager();
        $cmd    = $em->getClassMetadata($className);
        $db     = $em->getConnection();

        $db->query('SET FOREIGN_KEY_CHECKS=0');

        $db->executeUpdate(
            $db->getDatabasePlatform()->getTruncateTableSql($cmd->getTableName())
        );

        $db->query('SET FOREIGN_KEY_CHECKS=1');
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
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
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