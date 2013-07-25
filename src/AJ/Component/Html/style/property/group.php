<?php

namespace AJ\Template\Html\style\property;

use AJ\Template\Html\style\Property;

class Group extends Base {
	protected $style;
	protected $name;
	protected $args = array(
		'color'	=> array('item\\Color', 'transparent'),
		'width'	=> array('item\\Unit', 'medium thin thick'),
		'style'	=> array('Item', 'none solid dotted dashed double groove ridge inset outset'),
	);
	protected $value = array();
	
	
	function setValue($value) // #fff 1px solid
	{
		if(is_string($value))
		{
			$value = explode(' ', $value);
		}
		
		if(\arr::isFirstAssoc($value))
		{
			$value = array_intersect_key($value, $this->args);
			//debug($value);
			foreach($value as $prop_name => $val)
			{
				$this->__set($prop_name, $val);
			}
		}
		else {
			foreach(array_keys($this->args) as $i => $prop_name)
			{
				$this->__set($prop_name, $value[$i]);
				//debug("$prop_name: $value[$i]");
				
			}
		}
		
	}
	
	function __set($prop_name, $value)
	{
		$prop_name = str_replace('_', '-', $prop_name);
		
		if($prop = $this->__get($prop_name))
		{
			if($value === Property::VALUE_REMOVE)
			{
				unset($this->value[$prop_name]);
			}
			else {
				$prop->setValue($value);
			}
		}
	}
	
	function __get($prop_name)
	{
		$prop_name = str_replace('_', '-', $prop_name);
		
		if(isset($this->value[$prop_name]))
		{
			return $this->value[$prop_name];
		}
		if(isset($this->args[$prop_name]))
		{
			$this->value[$prop_name] = Property::forge($this->style, "{$this->name}-{$prop_name}", $this->args[$prop_name]);
			return $this->value[$prop_name];
		}
		
		return false;
	}

	
}

