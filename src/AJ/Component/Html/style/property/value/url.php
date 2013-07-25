<?php

namespace AJ\Template\Html\style\property\value;



class Url extends Base {
	const TYPE_VALID = 'valid_path';
	
	protected $path = '';
	protected $dir = '';
	protected $file = '';
	protected $extension = '';
	
	
	function setValue($value)
	{
		//debug($value);
		$this->type = static::TYPE_VALID;
		$this->value = $value;
		//parent::setValue($value);
		
	}
	
	function getValue()
	{
		//debug("$this->type: $this->value");
		if($this->type === static::TYPE_VALID)
		{
			return $this->value; //"url('$this->value')";
		}
		
		return \AJ\Template\Html\style\Property::VALUE_REMOVE;
	}
	
	
}

