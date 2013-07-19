<?php
namespace Acme\FormBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use \Doctrine\Common\Util\Debug;
class StorageType extends FormType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$t = $this->translator;		
		parent::buildForm($builder,$options);
		$builder->add('pricePerMeterMax', 'money',
			array('label' => $t->trans('Price per meter max'),
				'currency' => 'PLN'
			));	
		$builder->add('loadingRamp', 'choice',
			array('choices' => 
				array('no' => $t->trans('Yes'),
					'yes' => $t->trans('No')),
					'label' => $t->trans('Loading ramp'),
					'empty_value' => $t->trans('Choose an option'),					
			));	
		$builder->add('maneuverField', 'choice',
			array('choices' => 
				array('no' => $t->trans('Yes'),
					'yes' => $t->trans('No')),
					'label' => $t->trans('Maneuver field'),
					'empty_value' => $t->trans('Choose an option'),					
			));	
		parent::suffixForm($builder,$options);
	}
	public function getName()
	{
		return 'storage';
	}
}