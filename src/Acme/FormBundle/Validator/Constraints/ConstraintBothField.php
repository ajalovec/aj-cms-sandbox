<?php
namespace Acme\FormBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintBothField extends Constraint
{
    public $message = 'The string "%string%" contains an illegal character: it can only contain letters or numbers.';
	public $firstField;
	public $secondField;
	public $formName;
	
	public function __construct($options = null)
    {
        if (null !== $options && !is_array($options)) {
            $options = array(
                'firstField' => $options,
                'secondField' => $options,
                'formName' => $options,
            );
        }

        parent::__construct($options);

        if (null === $this->firstField && null === $this->secondField) {
            throw new MissingOptionsException('Either option "firstField" or "secondField" must be given for constraint ' . __CLASS__, array('firstField', 'secondField'));
        }
        if (null === $this->formName ) {
            throw new MissingOptionsException('Either option "formName" must be given for constraint ' . __CLASS__, array('firstField', 'secondField'));
        }
    }
	 public function validatedBy()
	 {
		 return "constraintbothfield";
	 }
	
}