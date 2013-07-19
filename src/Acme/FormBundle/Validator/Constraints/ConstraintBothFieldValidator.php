<?php
namespace Acme\FormBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\Container;

class ConstraintBothFieldValidator extends ConstraintValidator
{
	protected $container;
	protected $request;
	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->request = $this->container->get('request');
		
		
		
	}
    public function validate($value, Constraint $constraint)
    {
		$firstFieldName = $constraint->firstField;
		$secondFieldName = $constraint->secondField;
		$formArr = $this->request->request->get($constraint->formName);
		if(is_array($formArr)){
			$firstField = $formArr[$constraint->firstField];
			$secondField = $formArr[$constraint->secondField];
		}else{
			throw new \Exception('Niepoprawna nazwa formularza, formularz nie istnieje');
		}

		if(empty($firstField) && empty($secondField)){
			$this->context->addViolation("Complete at least one field of these fields: $firstFieldName, $secondFieldName");
		}
    }
}