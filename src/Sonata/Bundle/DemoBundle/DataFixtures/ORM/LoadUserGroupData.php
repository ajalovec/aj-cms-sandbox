<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Bundle\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserGroupData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    function getOrder()
    {
        return 1;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $manager = $this->getGroupManager();

        $group = $manager->createGroup("Editor 1");
        $group->setRoles(self::getUserRoles());

        $manager->updateGroup($group);

        $this->addReference('user-group-editor', $group);
    }


    /**
     * @return \FOS\UserBundle\Model\GroupManagerInterface
     */
    public function getGroupManager()
    {
        return $this->container->get('fos_user.group_manager');
    }

    private function getUserRoles()
    {
        return array(
            'ROLE_SONATA_PAGE_ADMIN_PAGE_LIST',
            'ROLE_SONATA_PAGE_ADMIN_PAGE_VIEW',
            'ROLE_SONATA_PAGE_ADMIN_BLOCK_EDIT',
            'ROLE_SONATA_PAGE_ADMIN_BLOCK_LIST',
            'ROLE_SONATA_PAGE_ADMIN_BLOCK_VIEW',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_EDIT',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_LIST',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_CREATE',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_VIEW',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_DELETE',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_EXPORT',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_OPERATOR',
            'ROLE_SONATA_PAGE_ADMIN_SNAPSHOT_MASTER',
            'ROLE_SONATA_NEWS_ADMIN_POST_EDIT',
            'ROLE_SONATA_NEWS_ADMIN_POST_LIST',
            'ROLE_SONATA_NEWS_ADMIN_POST_CREATE',
            'ROLE_SONATA_NEWS_ADMIN_COMMENT_EDIT',
            'ROLE_SONATA_NEWS_ADMIN_COMMENT_LIST',
            'ROLE_SONATA_NEWS_ADMIN_COMMENT_VIEW',
            'ROLE_SONATA_NEWS_ADMIN_COMMENT_DELETE',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_EDIT',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_LIST',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_CREATE',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_VIEW',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_DELETE',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_EXPORT',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_OPERATOR',
            'ROLE_SONATA_NEWS_ADMIN_CATEGORY_MASTER',
            'ROLE_SONATA_NEWS_ADMIN_TAG_EDIT',
            'ROLE_SONATA_NEWS_ADMIN_TAG_LIST',
            'ROLE_SONATA_NEWS_ADMIN_TAG_CREATE',
            'ROLE_SONATA_NEWS_ADMIN_TAG_VIEW',
            'ROLE_SONATA_NEWS_ADMIN_TAG_DELETE',
            'ROLE_SONATA_NEWS_ADMIN_TAG_EXPORT',
            'ROLE_SONATA_NEWS_ADMIN_TAG_OPERATOR',
            'ROLE_SONATA_NEWS_ADMIN_TAG_MASTER',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_EDIT',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_LIST',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_CREATE',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_VIEW',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_DELETE',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_EXPORT',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_OPERATOR',
            'ROLE_SONATA_MEDIA_ADMIN_MEDIA_MASTER',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_EDIT',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_LIST',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_CREATE',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_VIEW',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_DELETE',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_EXPORT',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_OPERATOR',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_MASTER',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_EDIT',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_LIST',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_CREATE',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_VIEW',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_DELETE',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_EXPORT',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_OPERATOR',
            'ROLE_SONATA_MEDIA_ADMIN_GALLERY_HAS_MEDIA_MASTER',
            'ROLE_SONATA_DEMO_ADMIN_CAR_EDIT',
            'ROLE_SONATA_DEMO_ADMIN_CAR_LIST',
            'ROLE_SONATA_DEMO_ADMIN_CAR_CREATE',
            'ROLE_SONATA_DEMO_ADMIN_CAR_DELETE',
            'ROLE_SONATA_DEMO_ADMIN_CAR_EXPORT',
            'ROLE_SONATA_DEMO_ADMIN_CAR_OPERATOR',
            'ROLE_SONATA_DEMO_ADMIN_CAR_MASTER',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_EDIT',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_LIST',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_CREATE',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_VIEW',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_DELETE',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_EXPORT',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_OPERATOR',
            'ROLE_SONATA_DEMO_ADMIN_ENGINE_MASTER',
            'ROLE_SONATA_NOTIFICATION_ADMIN_MESSAGE_EXPORT',
            'ROLE_ADMIN',
            'ROLE_USER',
            'ROLE_SONATA_NOTIFICATION_ADMIN_MESSAGE_MASTER',
            'ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT',
            'ROLE_SONATA_ADMIN',
            'SONATA',
            'ROLE_SONATA_NOTIFICATION_ADMIN',
        );
    }

}