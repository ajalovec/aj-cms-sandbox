<?php

namespace JMI\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function oPodjetjuAction($name = "")
    {
        return $this->render('JMISiteBundle:Default:o_podjetju.html.twig', array('name' => $name));
    }

    public function referenceAction()
    {
        return $this->render('JMISiteBundle:Default:reference.html.twig', array('name' => $name));
    }

    public function storitveAction()
    {
        return $this->render('JMISiteBundle:Default:storitve.html.twig', array('name' => $name));
    }

    /**
     * @param string $id
     *
     * @return MediaInterface
     */
    public function getMedia($id)
    {
        return $this->get('sonata.media.manager.media')->findOneBy(array('id' => $id));
    }

    /**
     * @throws NotFoundHttpException
     *
     * @param string $id
     * @param string $format
     *
     * @return Response
     */
    public function viewAction($id, $format = 'reference')
    {
        $media = $this->getMedia($id);

        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $id));
        }

        //if (!$this->get('sonata.media.pool')->getDownloadSecurity($media)->isGranted($media, $this->getRequest())) {
        //    throw new AccessDeniedException();
        //}

        return $this->render('SonataMediaBundle:Media:view.html.twig', array(
            'media'     => $media,
            'formats'   => $this->get('sonata.media.pool')->getFormatNamesByContext($media->getContext()),
            'format'    => $format
        ));
    }
}
