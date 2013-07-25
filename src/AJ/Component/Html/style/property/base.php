<?php

namespace AJ\Template\Html\style\property;



abstract class Base  {
	
	function __construct($scope, $name, $args = FALSE)
	{
		$this->style = $scope;
		$this->name = $name;
		$this->args = $args;
	}
	
	function getValue($as_object = false)
	{
		$result = $as_object ? new \stdClass() : array();
		
		foreach($this->value as $prop_name => $prop)
		{
			if($value = $prop->getValue() !== \AJ\Template\Html\style\Property::VALUE_REMOVE)
			{
				if($as_object)
				{
					$result->{$prop_name} = $prop->getValue(true);
				}
				else {
					$result[$prop_name] = $prop->getValue();
				}
			}
		}
		
		return $result;
	}
	
	abstract function setValue($value);
	
	
	function toCss($selector = '')
	{
		return $this->style->toCss($selector, $this->toString());
	}
	
	function toString()
	{
		return $this->style->toString($this->name, $this->getValue());
		//return \AJ\Template\Html\style\Property::render_array($this->getValue(), $this->name, $this->style->separator);
	}

	function __toString()
	{
		return $this->toString();
	}
	
}

