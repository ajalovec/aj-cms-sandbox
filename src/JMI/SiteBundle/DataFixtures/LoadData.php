<?php
namespace JMI\SiteBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\PageInterface;

class LoadData extends DataFixtureLoader implements OrderedFixtureInterface
{
    function getOrder()
    {
        return 100;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        $this->truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Site");
        $this->truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Page");
        
        $baseDir = __DIR__ . '/..';
        $baseDir = __DIR__;
        return  array(
            $baseDir . '/fixtures/bootstrap.yml',
            //$baseDir . '/fixtures/pages.yml',
            //$baseDir . '/fixtures/blocks.yml',
        );
    }

    public function truncateEntity($className)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $cmd    = $em->getClassMetadata($className);
        $db     = $em->getConnection();

        $db->query('SET FOREIGN_KEY_CHECKS=0');

        $db->executeUpdate(
            $db->getDatabasePlatform()->getTruncateTableSql($cmd->getTableName())
        );

        $db->query('SET FOREIGN_KEY_CHECKS=1');
    }
    
    public function strtotime($str = 'now')
    {
        return strtotime($str);
    }

    public function pageTemplate($name = 'default')
    {
        return $name;
    }

    public function getCmsPageRouteName()
    {
        return PageInterface::PAGE_ROUTE_CMS_NAME;
    }

    /**
     * @param SiteInterface $site
     */
    public function createHomePage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();

        $faker = $this->getFaker();

        $this->addReference('page-homepage', $homepage = $pageManager->create());
        $homepage->setSlug('/');
        $homepage->setUrl('/');
        $homepage->setName('homepage');
        $homepage->setEnabled(true);
        $homepage->setDecorate(0);
        $homepage->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $homepage->setTemplateCode('default');
        $homepage->setRouteName(PageInterface::PAGE_ROUTE_CMS_NAME);
        $homepage->setSite($site);

        $pageManager->save($homepage);

        // CREATE A HEADER BLOCK
        $homepage->addBlocks($content = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $homepage,
            'code' => 'content',
        )));

        $content->setName('The container container');

        $blockManager->save($content);

        // add a block text
        $content->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');
        $text->setSetting('content', <<<CONTENT
<h1>Welcome</h1>

<p>
    This page is a demo of the Sonata Sandbox available on <a href="https://github.com/sonata-project/sandbox">github</a>.
    This demo try to be interactive so you will be able to found out the different features provided by the Sonata's Bundle.
</p>

<p>
    First this page and all the other pages are served by the <code>SonataPageBundle</code>, a page is composed by different
    blocks. A block is linked to a service. For instance the current gallery is served by a
    <a href="https://github.com/sonata-project/SonataMediaBundle/blob/master/Block/GalleryBlockService.php">Block service</a>
    provided by the <code>SonataMediaBundle</code>.
</p>
CONTENT
);
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($homepage);

        // add a gallery
        $content->addChildren($gallery = $blockManager->create());
        $gallery->setType('sonata.media.block.gallery');
        $gallery->setSetting('galleryId', $this->getReference('media-gallery')->getId());
        $gallery->setSetting('title', $faker->sentence(4));
        $gallery->setSetting('context', 'default');
        $gallery->setSetting('format', 'big');
        $gallery->setPosition(2);
        $gallery->setEnabled(true);
        $gallery->setPage($homepage);

        $content->addChildren($text = $blockManager->create());
        $text->setType('sonata.block.service.text');

        $text->setPosition(3);
        $text->setEnabled(true);
        $text->setSetting('content', <<<CONTENT
<h3>Sonata's bundles</h3>

<p>
    Some bundles does not have direct visual representation as they provide services. However, others does have
    a lot to show :

    <ul>
        <li><a href="/admin/dashboard">Admin (SonataAdminBundle)</a></li>
        <li><a href="/blog">Blog (SonataNewsBundle)</a></li>
    </ul>
</p>
CONTENT
);

        $pageManager->save($homepage);
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
