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


abstract class BootstrapFixture extends AbstractFixture implements ContainerAwareInterface
{
    protected $container;
    protected $defaults = array();

    private $entityClass = array(
        "page"      => "Application\\Sonata\\PageBundle\\Entity\\Page",
        "snapshot"  => "Application\\Sonata\\PageBundle\\Entity\\Snapshot",
        "block"     => "Application\\Sonata\\PageBundle\\Entity\\Block",
        "site"      => "Application\\Sonata\\PageBundle\\Entity\\Site",
    );

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    
    //abstract protected function getFixtures();

    public function strtotime($str = 'now')
    {
        return strtotime($str);
    }
    public function PageTemplate($name = 'default')
    {
        return $name;
    }

    public function CmsRoute()
    {
        return PageInterface::PAGE_ROUTE_CMS_NAME;
    }

    protected function truncateEntity($name)
    {
        if(! $className = $this->entityClass[$name]) {
            return;
        }

        $em     = $this->container->get('sonata.page.entity_manager');
        $cmd    = $em->getClassMetadata($className);
        $db     = $em->getConnection();

        $db->query('SET FOREIGN_KEY_CHECKS=0');

        $db->executeUpdate(
            $db->getDatabasePlatform()->getTruncateTableSql($cmd->getTableName())
        );

        $db->query('SET FOREIGN_KEY_CHECKS=1');
    }


    public function parseYaml($file)
    {
        $data = (array) $this->loadFile($file);
        
        if(isset($data['defaults'])) {
            $this->defaults = array_replace_recursive($this->defaults, (array) $data['defaults']);
            unset($data['defaults']);
        }

        foreach ($data as $type => $value) {

            switch ($type) {
                case 'page':
                    $this->createNewInstances($type, $value, $this->getPageManager());
                    break;
                
                default:
                    # code...
                    break;
            }

        }

        //var_dump($this->defaults);

    }

    protected function createNewInstances($type, array $array, $manager)
    {
        foreach ($array as  $reference => $data) {
            list($referenceName, $referenceDefault) = explode('|', (string)$reference, 2);
            
            $defaultData = isset($this->defaults[$type]) && is_array($this->defaults[$type][$referenceDefault]) ? $this->defaults[$type][$referenceDefault] : array();
            
            $data = array_replace_recursive($defaultData, $data);
            $instance = $manager->create();

            var_dump($data);
        }


    }

    protected function createPage($data = array(), $callback = null)
    {
        $manager = $this->getPageManager();

        $page = $manager->create();
        
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

        $manager->save($page);
    }

    protected function loadFile($file)
    {
        if (!stream_is_local($file)) {
            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $file));
        }

        if (!file_exists($file)) {
            throw new InvalidArgumentException(sprintf('The service file "%s" is not valid.', $file));
        }
        
        return YamlParser::parse(file_get_contents($file));
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