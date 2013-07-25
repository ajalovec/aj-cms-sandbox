<?php

namespace AJ\Template\Html\style\property;

require_once 'value/base.php';
require_once 'value/custom.php';
require_once 'value/num.php';
require_once 'value/unit.php';
require_once 'value/url.php';
require_once 'value/color.php';

class Value extends value\Base  {
	const TYPE_CUSTOM 	= 'custom';
	
	function setValue($value)
	{
		if(!parent::setValue($value)) {
			$this->type = static::TYPE_CUSTOM;
			$this->value = $this->default;
		}
	}
}

