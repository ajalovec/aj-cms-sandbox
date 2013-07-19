<?php
namespace Acme\FormBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use \Doctrine\Common\Util\Debug;
class ParcelType extends FormType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$t = $this->translator;		
		parent::buildForm($builder,$options);
		$builder->add('propertyType', 'choice',
			array('choices' => 
				array('for building' => $t->trans('For building'),
					'agricultural' => $t->trans('Agricultural'),
					'another' => $t->trans('Another')),
					'label' => $t->trans('Property type'),
					'empty_value' => $t->trans('Choose an option'),					
			));
		$builder->add('media', 'choice',
			array('choices' => 
				array('gas' => $t->trans('Gas'),
					'current' => $t->trans('Current'),
					'water' => $t->trans('Water'),
					'sewerage' => $t->trans('Sewerage'),
					'cesspool' => $t->trans('Cesspool')),
					'multiple' => true,
					'expanded'  => true,
					'label' => $t->trans('Media'),			
			));	
		parent::suffixForm($builder,$options);

	}
	public function getName()
	{
		return 'parcel';
	}
}