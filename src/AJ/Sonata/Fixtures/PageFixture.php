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


abstract class PageFixture extends BaseFixture
{
    const NAMESPACE_DELIMITER = ":";
    //$referenceRepository
    
    public function createPage($namespace, array $data = array(), array $templateData = array())
    {
        //list($pageName) = static::splitNamespace($namespace, 1);
        //var_dump($this->referenceRepository->getReferences());
        $pageName = $namespace;
        $data = array_replace_recursive($templateData, $data);
        $data['parent'] = $this->getPage($data['parent']);

        $defaultData = array(
            'url'           => "/{$pageName}",
            'slug'          => $data['slug'] ?: $data['url'] ?: $pageName,
            'name'          => $pageName,
            'enabled'       => true,
            'decorate'      => (isset($data['routeName']) && $data['routeName'] !== PageInterface::PAGE_ROUTE_CMS_NAME ? 1 : 0),
            'requestMethod' => 'GET|POST|HEAD|DELETE|PUT',
            'templateCode'  => 'default',
            'routeName'     => PageInterface::PAGE_ROUTE_CMS_NAME,
            'site'          => $this->getReference('site'),
        );

        $page = $this->getPageManager()->create();

        static::populateEntity($page, array_replace_recursive($defaultData, $data));

        $this->addReference($namespace, $page);

        return $page;
    }

    public function getPage($namespace)
    {
        if($this->hasReference($namespace)) {
            return $this->getReference($namespace);
        }

        return null;
    }

    public function getContainerBlock($namespace)
    {
        if($this->hasReference($namespace)) {
            return $this->getReference($namespace);
        }

        return $this->createContainerBlock($namespace);
    }

    public function createContainerBlock($namespace, array $data = array())
    {
        list($pageName, $containerName) = static::splitNamespace($namespace, 1);

        $container = $this->getBlockInteractor()->createNewContainer(array(
            'enabled'   => true,
            'page'      => $this->getReference($pageName),
            'code'      => $containerName,
        ));

        static::populateEntity($container, $data);

        $this->addReference($namespace, $container);

        return $container;
    }

    public function createBlock($namespace, $type, array $settings = array())
    {
        list($blockName, $containerName) = static::splitNamespace($namespace, -1);
        
        $block          = $this->getBlockManager()->create();
        $container      = $this->getContainerBlock($containerName)->addChildren($block);

        $block->setType($type);
        $block->setEnabled(static::extractKey($settings, 'block.enabled', true));
        $block->setName(static::extractKey($settings, 'block.name', $blockName));
        $block->setPosition(static::extractKey($settings, 'block.position', $container->getChildren()->count()));
        $block->setSettings($settings);

        $this->addReference($namespace, $block);
        
        return $block;
    }

}