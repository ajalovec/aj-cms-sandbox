<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\AssortmentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Doctrine\Common\Util\Debug;

class AssortmentPageHasMediaAdmin extends Admin
{
    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->hasRequest()) {
            $link_parameters = array('context' => $this->getRequest()->get('context'));
        } else {
            $link_parameters = array();
        }
        $formMapper
            ->add('media', 'sonata_type_model_list_thumb', array(
            'required' => false,
            'model_manager' => $this->modelManager,
            'class' => 'Application\Sonata\MediaBundle\Entity\Media'
			), array(
                'link_parameters' => $link_parameters
            ))
            ->add('enabled', null, array('required' => false))
            ->add('position', 'hidden')
        ;
    }

    /**
     * @param  \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('media')
            ->add('assortmentPage')
            ->add('position')
            ->add('enabled')
        ;
    }

}
