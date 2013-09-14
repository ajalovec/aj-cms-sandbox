<?php
namespace Acme\ServicesBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ServicesAdmin extends Admin
{
    
    protected $translationDomain = 'SonataAdminBundle';
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('required' => true, 'label' => $this->trans('Name'), 'attr'=> array('class' => 'span6')))
            ->add('description', null, array('required' => false, 'label' => $this->trans('Description'), 'attr'=> array('class' => 'span8', 'rows'=>5)))
            ->add('bodyFormatter', 'sonata_formatter_type_selector', array(
                    'source' => 'body',
                    'target' => 'rawBody'
                ))
            ->add('body', null, array('label' => $this->trans('Body'), 'attr' => array('class' => 'span10', 'rows' => 20)))
        ;
    }
 
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => $this->trans('Id')))
            ->add('name', null,  array('label' => $this->trans('Name')))
            //->add('description', null,  array('label' => $this->trans('Description')))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        /*
            $collection
                    ->remove('create')
                    ->remove('delete')
                    ;
        */
    }
}