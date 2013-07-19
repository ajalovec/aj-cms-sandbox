<?php
namespace Acme\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\Translator;
/**
 * Formularz do edycji seo i routy
 */
class RouteSeoType extends AbstractType {
	
	protected $t;
	
	public function __construct(Translator $translator)
	{
		$this->t = $translator;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('seoTitle', null, array('required' => false,'label'  => 'Seo title'))	  	  
			->add('seoDescription', null, array('required' => false,'label'  => 'Seo description'))
			->add('seoKeyWords', null, array('required' => false,'label'  => 'Seo key words'))
			->add('isRouteStatic', null, array('label' => $this->t->trans('Isroutestatic') ))
			->add('pattern', null, array('required' => false,'label'  => 'Route'))	
        ;
    }
	
    public function getName()
    {
        return 'route_seo';
    }

    public function getDefaultOptions(array $options){
        return array('data_class' => 'Acme\PageBundle\Entity\Route');
    }
	
}
