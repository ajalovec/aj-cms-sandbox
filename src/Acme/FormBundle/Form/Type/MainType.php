<?php
// src/Acme/TaskBundle/Form/Type/TaskType.php
namespace Acme\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use \Doctrine\Common\Util\Debug;
class MainType extends FormType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$t = $this->translator;		
		$builder->add('action', 'choice',
			array('choices' => 
				array('buy-property' => $t->trans('Buy a property'),
					'rent-property' => $t->trans('Rent property')
				),
				'attr' => array('class' => 'property-action'),
				'label'=> $t->trans('I want to'),
				'empty_value' => $t->trans('Choose an option')
			));
		$builder->add('type', 'choice', 
			array('choices' => 
				array('hause' => $t->trans('Hause'),
					'flat' => $t->trans('Flat'),
					'local' => $t->trans('Local'),
					'parcel' => $t->trans('Parcel'),
					'storage' => $t->trans('Storage')
					),
				'attr' => array('class' => 'property-type'),
				'label'=> $t->trans('Property type'),
				'empty_value' => $t->trans('Choose an option')
			));
	}
	public function getName()
	{
		return 'main';
	}
}