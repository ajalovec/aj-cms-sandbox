<?php
namespace Acme\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Acme\FormBundle\Validator\Constraints\ConstraintBothField;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
class ContactFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface  $builder, array $options)
    {
    	$builder
        ->add('name', 'text', array('required'=> true, 'label'=>'Imie i nazwisko', 'constraints' => new NotBlank()))
		->add('phone', 'text', array('required'=> false, 'constraints' => new ConstraintBothField(array('firstField' => 'phone', 'secondField' => 'email', 'formName' => 'contact_form'))))
        ->add('email', 'email', array('required'=> false, 'constraints' => array(/*new ConstraintBothField(array('firstField' => 'phone', 'secondField' => 'email', 'formName' => 'contact_form')),*/ new Email() )))
        ->add('message', 'textarea', array('required'=> true, 'constraints' => new NotBlank()));
    }
    public function getName()
    {
        return 'contact_form';
    }

}