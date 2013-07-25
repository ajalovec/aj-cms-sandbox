<?php

namespace AJ\Template\Html\style\property\value;



abstract class Base  {
	const TYPE_BASE = 'base';
	const TYPE_DEFAULT 	= 'default';
	
	protected $type;
	protected $name;
	protected $style;
	protected $props = array();
	
	protected $value = NULL;
	protected $default = NULL;
	
	
	function __construct($scope, $name, $args = FALSE)
	{
		$this->style = $scope;
		$this->name = $name;
		$this->type = static::TYPE_BASE;
		
		if(is_string($args))
		{
			$args = explode(' ', trim($args));
			$this->props = $args;
			$this->default = reset($args);
		}
		else {
			$this->props = explode(' ', trim($args['props']));
			$this->default = isset($args['default']) ? $args['default'] : reset($this->props);
		}
		array_unshift($this->props, NULL, FALSE);
		//debug($args);
		
	}
	
	function getValue()
	{
		return $this->value;
	}
	
	function setValue($value)
	{
		if(in_array($value, $this->props))
		{
			$this->value = $value;
			$this->type = static::TYPE_BASE;
			return true;
		}
		
		return false;
	}
	
	function __toString()
	{
		return (string) $this->getValue();
	}
	
	//function __set($name, $value) {}
	
	
}

