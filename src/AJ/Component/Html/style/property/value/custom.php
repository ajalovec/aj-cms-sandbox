<?php

namespace AJ\Template\Html\style\property\value;


class Custom extends Base  {
	const TYPE_CUSTOM 	= 'custom';
	
	function setValue($value)
	{
		if(!parent::setValue($value)) {
			$this->type = static::TYPE_CUSTOM;
			$this->value = $value;
		}
	}
	
}

