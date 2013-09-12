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

use Sonata\MediaBundle\Model\GalleryInterface;
use Sonata\MediaBundle\Model\MediaInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class LoadMediaData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;
    private $testMedia = array(
        //"files" => Finder::create()
        //            ->name('*.JPG')
        //            ->in(__DIR__.'/../data/files'),
        "videos" => array(
            'ocAyDZC2aiU' => 'sonata.media.provider.youtube',
            'xdw0tz'      => 'sonata.media.provider.dailymotion',
            '9636197'     => 'sonata.media.provider.vimeo'
        )
    );

    function getOrder()
    {
        return 20;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        
        $this->createGallery(array(
            "files" => array(__DIR__.'/../fixtures/media/A-Banka', '*.JPG'),
            "name" => "A-Banka",
            "description" => "Dobava in montaža talnih konvektorjev in zračne zavese.",
            "defaultFormat" => "small",
            "context" => "default",
        ));

    }

    public function createGallery(array $data)
    {
        $faker      = $this->getFaker();
        $manager    = $this->getGalleryManager();
        $gallery    = $manager->create();
        
        $this->loadMediaData($gallery, $data);
        $gallery->setEnabled(true);
        $gallery->setName($data['name'] ?: $faker->sentence(12));
        $gallery->setDescription($data['description'] ?: null);
        $gallery->setContext($data['context'] ?: 'default');
        $gallery->setDefaultFormat($data['defaultFormat'] ?: 'small');

        $manager->update($gallery);

        $this->addReference('gallery-'.$gallery->getName(), $gallery);
    }

    private function loadMediaData($gallery, array $mediaData = array())
    {
        $faker = $this->getFaker();
        $manager      = $this->getMediaManager();
        $files = Finder::create()
                    ->name($mediaData['files'][1])
                    ->in($mediaData['files'][0]);

        
        $i = 0;
        foreach ($files as $pos => $file) {
            $media = $manager->create();
            $media->setBinaryContent($file);
            $media->setEnabled(true);
            //$media->setDescription($faker->sentence(15));

            $manager->save($media, 'default', 'sonata.media.provider.image');

            $this->addMedia($gallery, $media);
        }

        if(is_array($mediaData['videos'])) {
            foreach ($mediaData['videos'] as $video => $provider) {
                $media = $manager->create();
                $media->setBinaryContent($video);
                $media->setEnabled(true);

                $manager->save($media, 'default', $provider);

                $this->addMedia($gallery, $media);
            }
        }
    }

    /**
     * @param \Sonata\MediaBundle\Model\GalleryInterface $gallery
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @return void
     */
    public function addMedia(GalleryInterface $gallery, MediaInterface $media)
    {
        $galleryHasMedia = new \Application\Sonata\MediaBundle\Entity\GalleryHasMedia();
        $galleryHasMedia->setMedia($media);
        $galleryHasMedia->setPosition(count($gallery->getGalleryHasMedias()) + 1);
        $galleryHasMedia->setEnabled(true);

        $gallery->addGalleryHasMedias($galleryHasMedia);
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

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->container->get('faker.generator');
    }
}