<?php
namespace Acme\FormBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use \Doctrine\Common\Util\Debug;
class FlatType extends FormType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$t = $this->translator;		
		parent::buildForm($builder,$options);
		$builder->add('roomsCount', 'integer',
			array('label' => $t->trans('Rooms count')
			));	
		$builder->add('storey', 'integer',array('label' => $t->trans('Storey')));	
		$builder->add('priceMax', 'money',
			array('label' => $t->trans('Price max'),
				'currency' => 'PLN'
			));	
		$builder->add('blockType', 'choice',
			array('choices' => 
				array('high-rise' => $t->trans('High-rise'),
					'another' => $t->trans('Another')),
					'label' => $t->trans('Block type'),
					'empty_value' => $t->trans('Choose an option'),						
				));				
		$builder->add('standard', 'choice', 
			array('choices' => 
				array('to-live' => $t->trans('To live'),
					'to-refresh' => $t->trans('To refresh'),
					'to-restored' => $t->trans('To restored')),
					'label' => $t->trans('Standard'),
					'empty_value' => $t->trans('Choose an option'),					
			));
		parent::suffixForm($builder,$options);
	}
	public function getName()
	{
		return 'flat';
	}
}