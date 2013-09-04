<?php

namespace JMI\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


class ReferenceController extends Controller
{

    /**
     * @Route("")
     */
    public function indexAction($id = null)
    {
        $galleries = $this->getGalleryManager()->findBy(array(
            'enabled' => true
        ));

        return $this->render('JMISiteBundle:Reference:index.html.twig', array(
            'galleries'   => $galleries,
        ));
    }

    /**
     * @Route("/galerija/{id}"
     *  , requirements={"id" = "\d+"}
     *  , defaults={"id" = 1, "culture" = "si"}
     * )
     */
    public function galerijaAction($id)
    {
        $gallery = $this->getGalleryManager()->findOneBy(array(
            'id'      => $id,
            'enabled' => true
        ));

        if (!$gallery) {
            throw new NotFoundHttpException('unable to find the gallery with the id');
        }

        return $this->render('JMISiteBundle:Reference:galerija.html.twig', array(
            'gallery'   => $gallery,
        ));
    }

    /**
     * @Route("/galerija/{id}/{media_id}"
     *  , requirements={"id" = "\d+", "media_id" = "\d+"}
     *  , defaults={"culture" = "si", "format" = "reference"}
     * )
     */
    public function galerijaPoglejAction($id, $media_id, $format = 'reference')
    {
        $media = $this->getMedia($media_id);

        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $media_id));
        }

        return $this->render('JMISiteBundle:Reference:galerijaPoglej.html.twig', array(
            'media'     => $media,
            'formats'   => $this->get('sonata.media.pool')->getFormatNamesByContext($media->getContext()),
            'format'    => $format
        ));
    }


    public function getMedia($id)
    {
        return $this->get('sonata.media.manager.media')->findOneBy(array('id' => $id));
    }

    public function getMediaManager()
    {
        return $this->get('sonata.media.manager.media');
    }

    public function getGalleryManager()
    {
        return $this->get('sonata.media.manager.gallery');
    }
}
