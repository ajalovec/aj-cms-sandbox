<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Acme\AssortmentBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Util\Debug;

use Sonata\AdminBundle\Form\DataTransformer\ModelToIdTransformer;

/**
 * This type is used to render an hidden input text and 3 links
 *   - an add form modal
 *   - a list modal to select the targetted entities
 *   - a clear selection link
 */
class ModelThumbListType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	/*echo "<pre>";
    	Debug::dump($options);
		echo "</pre>";*/
        $builder
            ->resetViewTransformers()
            ->addViewTransformer(new ModelToIdTransformer($options['model_manager'], $options['class']))
    	;
	}

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    	//echo "<pre>";
    	//Debug::dump($view->all());
		//echo "</pre>";
		//die();
		
        if (isset($view->vars['sonata_admin'])) {
            // set the correct edit mode
            $view->vars['sonata_admin']['edit'] = 'list';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'model_manager'     => null,
            'class'             => null,
            'parent'            => 'text',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'sonata_type_model_list';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'sonata_type_model_list_thumb';
    }
}
