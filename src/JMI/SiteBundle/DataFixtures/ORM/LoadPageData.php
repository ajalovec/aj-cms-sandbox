<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
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
use AJ\Sonata\Fixtures\BaseFixture;

class LoadPageData extends BaseFixture implements OrderedFixtureInterface
{

    function getOrder()
    {
        return 100;
    }
    protected function getEntityManager()
    {
        return $this->container->get('sonata.media.entity_manager');;
    }

    protected function truncateEntity($className = null)
    {
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Page");
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Snapshot");
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Block");
        parent::truncateEntity("Application\\Sonata\\PageBundle\\Entity\\Site");
    }

    public function load(ObjectManager $manager)
    {
        $this->truncateEntity();
        
        $site = $this->createSite();
        //$this->createGlobalPage($site);
        $this->createHomePage($site);
        //$this->createBlogIndex($site);
        //$this->createGalleryIndex($site);
        //$this->createMediaPage($site);
        //$this->createUserPage($site);
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

        return $site;
    }

    
    
    public function createGlobalPage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();

        $global = $pageManager->create();
        $global->setName('global');
        $global->setRouteName('_page_internal_global');
        $global->setSite($site);

        $pageManager->save($global);

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

        $global->addBlocks($footer = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $global,
            'code' => 'footer',
        )));

        $footer->setName('The footer container');

        $footer->addChildren($text = $blockManager->create());

        $text->setType('aj_template_component.block.service.text_block');
        $text->setSetting('content', <<<FOOTER
        <a href="http://www.sonata-project.org">Sonata Project</a> sandbox demonstration.

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25614705-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
FOOTER
);
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($global);

        $pageManager->save($global);
    }
    const NAMESPACE_DELIMITER = ":";

    public function createContainerBlock($namespace, $name = null)
    {
        list($pageName, $containerName) = explode(self::NAMESPACE_DELIMITER, $namespace, 2);

        $page = $this->getReference($pageName);
        
        $container = $this->getBlockInteractor()->createNewContainer(array(
            'enabled'   => true,
            'page'      => $page,
            'code'      => $containerName,
        ));
        $container->setName($name ?: $containerName);

        $page->addBlocks($container);
        $this->getBlockManager()->save($container);

        $this->addReference($namespace, $container);

        return $container;
    }
    protected $currentPage = null;

    public function createBlock($namespace, $type, array $settings = array())
    {
        $strpos = strrpos($namespace, self::NAMESPACE_DELIMITER);
        $containerName  = substr($namespace, 0, $strpos);
        $blockName      = substr($namespace, $strpos+1);

        $block = $this->getBlockManager()->create();
        $block->setEnabled(true);
        $block->setType($type);
        $block->setName($blockName);
        $block->setSettings($settings);
        //$block->setPosition($data['position']);
        
        $container = $this->getReference($containerName);
        $container->addChildren($block);

        $this->addReference($namespace, $block);
        
        return $block;
    }
    
    /**
     * @param SiteInterface $site
     */
    public function createHomePage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();


        $this->addReference('homepage', $homepage = $pageManager->create());
        $homepage->setSlug('/');
        $homepage->setUrl('/');
        $homepage->setName('homepage');
        $homepage->setEnabled(true);
        $homepage->setDecorate(0);
        $homepage->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $homepage->setTemplateCode('default');
        $homepage->setRouteName(PageInterface::PAGE_ROUTE_CMS_NAME);
        $homepage->setSite($site);


        // CONTAINERS
        $this->createContainerBlock('homepage:content');
        $this->createContainerBlock('homepage:content_left');
        
        // BLOCKS
        $gallery = $this->createBlock("homepage:content:galerija", 'sonata.media.block.gallery', array(            
            'galleryId' => 1, //$this->getReference('gallery-A-Banka')->getId(),
            'title'     => "A-Banka", //$this->getReference('gallery-A-Banka')->getName(),
            'context'   => 'default',
            'format'    => 'big',
        ));
        $gallery->setPosition(1);
        

        $text = $this->createBlock('homepage:content_left:text 1', 'acme_content.block.service.content', array(
            'contentId' => 1,
        ));
        $text->setPosition(2);


        

        $pageManager->save($homepage);
    }

    /**
     * @param SiteInterface $site
     */
    public function createMediaPage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();

        $this->addReference('page-media', $media = $pageManager->create());
        $media->setSlug('/media');
        $media->setUrl('/media');
        $media->setName('Media & Seo');
        $media->setEnabled(true);
        $media->setDecorate(1);
        $media->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $media->setTemplateCode('default');
        $media->setRouteName('sonata_demo_media');
        $media->setSite($site);
        $media->setParent($this->getReference('page-homepage'));

        $pageManager->save($media);
    }

    /**
     * @param SiteInterface $site
     */
    public function createUserPage(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();

        $this->addReference('page-user', $userPage = $pageManager->create());
        $userPage->setSlug('/user');
        $userPage->setUrl('/user');
        $userPage->setName('Admin');
        $userPage->setEnabled(true);
        $userPage->setDecorate(1);
        $userPage->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $userPage->setTemplateCode('default');
        $userPage->setRouteName('page_slug');
        $userPage->setSite($site);
        $userPage->setParent($this->getReference('page-homepage'));

        $userPage->addBlocks($content = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $userPage,
            'code' => 'content_top',
        )));

        $content->setName('The content_top container');

        // add a block text
        $content->addChildren($text = $blockManager->create());
        $text->setType('aj_template_component.block.service.text_block');
        $text->setSetting('content', <<<CONTENT

<h2>Admin Bundle</h2>

<div>
    You can connect to the <a href="/admin/dashboard">admin section</a> by using two different accounts : <br>

    <ul>
        <li>Login: admin - Password: admin</li>
        <li>Login: secure - Password: secure - Key: 4YU4QGYPB63HDN2C</li>
    </ul>

    <h3>Two Step Verification</h3>
    The <b>secure</b> account is a demo of the Two Step Verification provided by
    the <a href="http://sonata-project.org/bundles/user/2-0/doc/reference/two_step_validation.html">Sonata User Bundle</a>

    <br />
    <br />
    <center>
        <img src="/bundles/sonatademo/images/secure_qr_code.png" class="img-polaroid" />
        <br />
        <em>Take a shot of this QR Code with <a href="https://support.google.com/accounts/bin/answer.py?hl=en&answer=1066447">Google Authenticator</a></em>
    </center>

</div>

CONTENT
);
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($userPage);


        $pageManager->save($userPage);
    }

    /**
     * @param SiteInterface $site
     */
    public function createBlogIndex(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();

        $blogIndex = $pageManager->create();
        $blogIndex->setSlug('blog');
        $blogIndex->setUrl('/blog');
        $blogIndex->setName('News');
        $blogIndex->setEnabled(true);
        $blogIndex->setDecorate(1);
        $blogIndex->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $blogIndex->setTemplateCode('default');
        $blogIndex->setRouteName('sonata_news_home');
        $blogIndex->setParent($this->getReference('page-homepage'));
        $blogIndex->setSite($site);

        $pageManager->save($blogIndex);
    }

    /**
     * @param SiteInterface $site
     */
    public function createGalleryIndex(SiteInterface $site)
    {
        $pageManager = $this->getPageManager();
        $blockManager = $this->getBlockManager();
        $blockInteractor = $this->getBlockInteractor();

        $galleryIndex = $pageManager->create();
        $galleryIndex->setSlug('gallery');
        $galleryIndex->setUrl('/media/gallery');
        $galleryIndex->setName('Gallery');
        $galleryIndex->setEnabled(true);
        $galleryIndex->setDecorate(1);
        $galleryIndex->setRequestMethod('GET|POST|HEAD|DELETE|PUT');
        $galleryIndex->setTemplateCode('default');
        $galleryIndex->setRouteName('sonata_media_gallery_index');
        $galleryIndex->setParent($this->getReference('page-homepage'));
        $galleryIndex->setSite($site);

        // CREATE A HEADER BLOCK
        $galleryIndex->addBlocks($content = $blockInteractor->createNewContainer(array(
            'enabled' => true,
            'page' => $galleryIndex,
            'code' => 'content_top',
        )));

        $content->setName('The content_top container');

        // add a block text
        $content->addChildren($text = $blockManager->create());
        $text->setType('aj_template_component.block.service.text_block');
        $text->setSetting('content', <<<CONTENT

<p>
    This current text is defined in a <code>text block</code> linked to a custom symfony action <code>GalleryController::indexAction</code>
    the SonataPageBundle can encapsulate an action into a dedicated template. <br /><br />

    If you are connected as an admin you can click on <code>Show Zone</code> to see the different editable areas. Once
    areas are displayed, just double click on one to edit it.
</p>

<h1>Gallery List</h1>
CONTENT
);
        $text->setPosition(1);
        $text->setEnabled(true);
        $text->setPage($galleryIndex);


        $pageManager->save($galleryIndex);
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