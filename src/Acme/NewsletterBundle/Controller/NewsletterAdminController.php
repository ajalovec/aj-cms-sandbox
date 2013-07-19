<?php
namespace Acme\NewsletterBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Util\Debug;
class NewsletterAdminController extends Controller
{	
    public function adminNewsletterSendAction()
    {
    	$id = $this->get('request')->get($this->admin->getIdParameter());
		$object = $this->admin->getObject($id);
		
        return $this->render("ApplicationSonataAdminBundle:AdminPage:page.html.twig", array(
            'action' => 'edit',
            'object' => $object
        ));
    }
	public function sendNewsletter($emails, $object)
	{
		
		$emailComponent = $this->container->get('acme_email.email.component');
		$sender = $emailComponent->getEmailsByType('default_sender');
		$recipients = $emails;
		$templateArr = array();
		$emailComponent->emailSend($sender,$recipients,"AcmeNewsletterBundle:Email:email.html.twig",$object,$object->getSubject(),array('processSenders'=>true,'processRecipients'=>false));
		$emailComponent->fillFlashBag(true,'sonata_flash_success','sonata_flash_error');
	
		$object->setSendTime(new \DateTime("now"));
		$this->admin->update($object);
		
		return $this->redirect($this->admin->generateObjectUrl('admin_newsletter_send',$object));
	}
    /**
     * return the Response object associated to the edit action
     *
     *
     * @param mixed $id
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Response
     */
    public function createEditAction($id = null, $action = 'update')
    {
        // the key used to lookup the template
        $templateKey = 'edit';

		if($action != 'create'){
			$id = $this->get('request')->get($this->admin->getIdParameter());
			$object = $this->admin->getObject($id);
	        if (!$object) {
	            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
	        }
	
	        if (false === $this->admin->isGranted('EDIT', $object)) {
	            throw new AccessDeniedException();
        	}
		}else{
	        if (false === $this->admin->isGranted('CREATE')) {
	            throw new AccessDeniedException();
	        }
	        $object = $this->admin->getNewInstance();
		}

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
            	if($action != 'create'){
            		$this->admin->update($object);
            	}else{
            		$this->admin->create($object);
            	}

                $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');
                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result'    => 'ok',
                        'objectId'  => $this->admin->getNormalizedIdentifier($object)
                    ));
                }
				$request = $this->get('request')->request;

				if($request->get('btn_newsletter_send')){
					return $this->sendNewsletter($request->get('emails'),$object);				
				}
                return $this->redirectTo($object);
            }
            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->get('session')->setFlash('sonata_flash_error', 'flash_edit_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
            }
        }
        $view = $form->createView();
        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

		$currentRoute = $this->container->get('request')->get('_route');
		$usersAdminList = $this->getUsersList();
		//Debug::dump($usersAdminList);
		//die();
        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'edit',
            'form'   => $view,
            'object' => $object,
            'usersAdminList' => $usersAdminList,
        ));
    }
	
	public function createAction()
	{
		return $this->createEditAction(null,'create');
	}
	public function editAction($id = null)
	{
		return $this->createEditAction($id,'update');
	}
	public function getUsersList()
	{
        $queryBuilder = $this->getDoctrine()->getEntityManager()
            ->createQueryBuilder(array('p', 'c'))
            ->select('p')
            ->from('AcmeUserBundle:User', 'p')
			//->leftJoin('p.relationship', 'c')
			//->orderBy('c.root, c.lft', 'ASC')
			->getQuery();
			
		$usersList = $queryBuilder->getResult();
		return $usersList;
	}
}
