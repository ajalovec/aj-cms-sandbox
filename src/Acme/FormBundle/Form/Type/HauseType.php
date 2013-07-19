<?php
// src/Acme/TaskBundle/Form/Type/TaskType.php
namespace Acme\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use \Doctrine\Common\Util\Debug;
class HauseType extends FormType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder,$options);
		$t = $this->translator;
		$builder->add('buildingType', 'choice', 
			array(
			'choices' => 
				array('detached' => $t->trans('Detached'),
					'terraced' => $t->trans('Terraced'),
					'ground-floor' => $t->trans('Ground-floor'),
					'storied' => $t->trans('Storied')),
					'label' => $t->trans('Building type'),
					'empty_value' => $t->trans('Choose an option'),
			));
		$builder->add('roomsCount', 'integer',
			array('label' => $t->trans('Rooms count')
			));
		$builder->add('priceMax', 'money',
			array('label' => $t->trans('Price max'),
				'currency' => 'PLN'
			));
		parent::suffixForm($builder,$options);
	}
	public function getName()
	{
		return 'hause';
	}
}