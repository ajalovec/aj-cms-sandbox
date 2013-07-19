<?php

namespace Acme\UserBundle\Controller;
use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\Request;

class SecurityAdminController extends BaseSecurityController
{
    protected function renderLogin(array $data)
    {
        $template = sprintf('AcmeUserBundle:Security:adminLogin.html.%s', $this->container->getParameter('fos_user.template.engine'));
        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
