<?php

/*
 * This file is part of the AJSonata package.
 *
 * (c) Andraž Jalovec <andraz.jalovec@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JMI\SiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\PageInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AJ\Sonata\Fixtures\PageFixture;

class LoadPageData extends PageFixture implements OrderedFixtureInterface
{
        const NAMESPACE_DELIMITER = ":";

    function getOrder()
    {
        return 100;
    }
    protected function getEntityManager()
    {
        return $this->container->get('sonata.media.entity_manager');;
    }

    public function truncate()
    {
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Page");
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Snapshot");
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Block");
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Site");
    }

    public function create(ObjectManager $manager)
    {   
        $site = $this->createSite();
        //$this->createGlobalPage($site);
        $this->createHomePage();
        $this->createTestPage();
        $this->createStoritvePage();
    }

    public function createSite()
    {
        $site = $this->getSiteManager()->create();

        $site->setHost('localhost');
        $site->setEnabled(true);
        $site->setName('localhost');
        $site->setEnabledFrom(new \DateTime('now'));
        $site->setEnabledTo(new \DateTime('+10 years'));
        $site->setRelativePath("");
        $site->setIsDefault(true);

        $this->getSiteManager()->save($site);
        $this->addReference('site', $site);

        return $site;
    }

    /**
     * @param SiteInterface $site
     */
    public function createHomePage()
    {
        $page = $this->createPage('homepage', array(
            'url'           => '/',
            'name'          => 'homepage',
            'templateCode'  => 'default',
        ));

        // CONTAINERS
        $this->createContainerBlock('homepage:content');
        $this->createContainerBlock('homepage:content_left');
        
        // BLOCKS
        $this->createBlock("homepage:content:galerija", 'sonata.media.block.gallery', array(            
            'galleryId' => 1, //$this->getReference('gallery-A-Banka')->getId(),
            'title'     => "A-Banka", //$this->getReference('gallery-A-Banka')->getName(),
            'context'   => 'default',
            'format'    => 'big',
        ));
        

        $this->createBlock('homepage:content:text', 'acme_content.block.service.content', array(
            'contentId' => 1,
        ));

        $this->createBlock('homepage:content_left:text', 'acme_content.block.service.content', array(
            'contentId' => 1,
        ));

        $this->getPageManager()->save($page);
    }
    /**
     * @param SiteInterface $site
     */
    public function createTestPage()
    {
        $page = $this->createPage('test', array(
            'parent'        => 'homepage',
        ));
        
        $this->getPageManager()->save($page);
    }
    /**
     * @param SiteInterface $site
     */
    public function createStoritvePage()
    {
        $page = $this->createPage('services', array(
            'parent'        => 'homepage',
            'url'           => '/storitve',
            'name'          => 'Storitve',
            'routeName'     => 'acme_services_index',
        ));

        // BLOCKS
        $content1 = <<<CONTENT
<p>Nudimo naslednje storitve:</p>
<ul>
    <li>Radiatorsko ogrevanje</li>
    <li>Stensko ogrevanje in hlajenje</li>
    <li>Toplotne črpalke</li>
    <li>Talno ogrevanje</li>
</ul>

<h1>Seznam referenc</h1>
CONTENT;
        $this->createBlock('services:content:text', 'aj_template_component.block.service.text_block', array(
            'content' => $content1,
        ));
        
        $this->createBlock("services:content:galerija", 'sonata.media.block.gallery', array(            
            'galleryId' => 1, //$this->getReference('gallery-A-Banka')->getId(),
            'title'     => "A-Banka", //$this->getReference('gallery-A-Banka')->getName(),
            'context'   => 'default',
            'format'    => 'big',
        ));

        $this->getPageManager()->save($page);
    }


    public function createGlobalPage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();

        $global = $this->createPage('global', array(
            //'url'           => '',
            'routeName'     => '_page_internal_global',
        ));

        $this->getPageManager()->save($global);

        // CREATE A HEADER BLOCK
        $global->addBlocks($title = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $global,
            'code' => 'title',
        )));

        $title->setName('The title container');

        $title->addChildren($text = $blockManager->create());

        $text->setType('aj_template_component.block.service.text_block');
        $text->setSetting('content', '<h2><a href="/">Sonata Sandbox</a></h2>');
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($global);

        $global->addBlocks($header = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $global,
            'code' => 'header',
        )));

        $header->setName('The header container');

        $header->addChildren($menu = $blockManager->create());

        $menu->setType('sonata.page.block.children_pages');
        $menu->setSetting('current', false);
        $menu->setPosition(1);
        $menu->setEnabled(true);
        $menu->setPage($global);

       
        $this->getPageManager()->save($global);
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