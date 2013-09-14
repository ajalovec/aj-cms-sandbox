<?php
namespace Cnj\Cms\ContentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ContentAdmin extends Admin
{
    protected $translationDomain = 'SonataAdminBundle';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('group', null, array('required' => false, 'label' => $this->trans('Group')))
            ->add('parent', null, array('required' => false, 'label' => $this->trans('Parent')))
            ->add('title', null, array('required' => false, 'label' => $this->trans('Title'), 'attr'=> array()))
            ->add('description', null, array('required' => false, 'label' => $this->trans('Description'), 'attr'=> array()))
            ->add('bodyFormatter', 'sonata_formatter_type', array(
                    'format_field'   => 'bodyFormatter',
                    'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                    'source_field' => 'rawBody',
                    'target_field' => 'body'
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => $this->trans('Title')))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, array('label' => $this->trans('Title')))
            ->add('parent', null,  array('label' => $this->trans('Parent')))
            ->add('description', null,  array('label' => $this->trans('Description')))
        ;
    }
}
