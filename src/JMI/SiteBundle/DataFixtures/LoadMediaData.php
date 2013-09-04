<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageDataBundle\DataFixtures\ORM;

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

    function getOrder()
    {
        return 201;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $mediaData = array(
            "files" => Finder::create()
                        ->name('*.JPG')
                        ->in(__DIR__.'/../data/files'),
            "videos" => array(
                'ocAyDZC2aiU' => 'sonata.media.provider.youtube',
                'xdw0tz'      => 'sonata.media.provider.dailymotion',
                '9636197'     => 'sonata.media.provider.vimeo'
            ),
        );
        $this->createGallery($mediaData, function($gallery) {
            $gallery->setName($galleryName);
            $gallery->setDefaultFormat('small');
            $gallery->setContext('default');
        })
    }

    public function createGallery($mediaData, $galleryDataCallback)
    {
        $gallery = $this->getGalleryManager()->create();
        
        $this->addMediaData($gallery, $mediaData);
        $gallery->setEnabled(true);
        $gallery->setDefaultFormat('small');
        $gallery->setContext('default');
        
        $galleryDataCallback($gallery);

        $this->getGalleryManager()->update($gallery);

        $this->addReference('media-gallery', $gallery);
    }

    public function addMediaData($gallery, $mediaData)
    {
        $faker = $this->getFaker();
        $media      = $this->getMediaManager();

        $i = 0;
        foreach ($mediaData['files'] as $pos => $file) {
            $media = $media->create();
            $media->setBinaryContent($file);
            $media->setEnabled(true);
            $media->setDescription($faker->sentence(15));

            $media->save($media, 'default', 'sonata.media.provider.image');

            $this->addMedia($gallery, $media);
        }

        foreach ($mediaData['videos'] as $video => $provider) {
            $media = $media->create();
            $media->setBinaryContent($video);
            $media->setEnabled(true);

            $media->save($media, 'default', $provider);

            $this->addMedia($gallery, $media);
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