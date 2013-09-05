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


abstract class BootstrapFixture extends DataFixtureLoader implements ContainerAwareInterface, OrderedFixtureInterface
{
    protected $container;
    abstract protected $order;

    public function getOrder()
    {
        return (int) $this->order;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function truncateEntity($className)
    {
        $em     = $this->container->get('doctrine.orm.entity_manager');
        $cmd    = $em->getClassMetadata($className);
        $db     = $em->getConnection();

        $db->query('SET FOREIGN_KEY_CHECKS=0');

        $db->executeUpdate(
            $db->getDatabasePlatform()->getTruncateTableSql($cmd->getTableName())
        );

        $db->query('SET FOREIGN_KEY_CHECKS=1');
    }


    protected function createSite()
    {
        $site = $this->getSiteManager()->create();

        $site->setHost('localhost');
        $site->setEnabled(true);
        $site->setName('jmi');
        $site->setEnabledFrom(new \DateTime('now'));
        $site->setEnabledTo(new \DateTime('+20 years'));
        $site->setRelativePath("/_git/aj-cms-sandbox/web");
        $site->setIsDefault(true);

        $this->getSiteManager()->save($site);

        return $site;
    }

    protected function createPage($data = array(), $callback = null)
    {
        $pageManager = $this->getPageManager();

        $page = $pageManager->create();
        
        $page->setSlug('/');
        $page->setUrl('/');
        $page->setName('homepage');
        $page->setEnabled(true);
        $page->setDecorate(0);
        $page->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $page->setTemplateCode('default');
        $page->setRouteName(PageInterface::PAGE_ROUTE_CMS_NAME);
        $page->setSite($site);

        $this->addReference('page-homepage', $page);

        $pageManager->save($page);
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
}