<?php


namespace Acme\TreeBundle\Controller;
 
use Sonata\AdminBundle\Controller\CRUDController as Controller;
 
class TestAdminController extends Controller
{
    public function listAction()
    {
    	echo "oto lista boza";
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'   => 'list',
            'form'     => $formView,
            'datagrid' => $datagrid
        ));
    } 
}