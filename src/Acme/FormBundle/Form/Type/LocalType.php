<?php
namespace Acme\FormBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use \Doctrine\Common\Util\Debug;
class LocalType extends FormType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$t = $this->translator;		
		parent::buildForm($builder,$options);
		$builder->add('roomsCount', 'integer',
			array('label' => $t->trans('Rooms count')
			));	
		$builder->add('storey', 'integer',array('label' => $t->trans('Storey')));	
		$builder->add('pricePerMeterMax', 'money',
			array('label' => $t->trans('Price per meter max'),
				'currency' => 'PLN'
			));	
		parent::suffixForm($builder,$options);
	}
	public function getName()
	{
		return 'local';
	}
}