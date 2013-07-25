<?php

namespace AJ\Template\Html\style\property;

use AJ\Template\Html\style\Property;

class Repeat extends Base {
	protected $style;
	protected $name;
	protected $args 	= array(
		'groups' => 'top right bottom left',
		'props' => array(
			'color'	=> array('item\\Color', 'transparent'),
			'width'	=> array('item\\Unit', 'medium thin thick'),
			'style'	=> array('Item', 'none solid dotted dashed double groove ridge inset outset'),
		)
	);
	protected $value 	= array();
	
	protected $groups = array();
	protected $props;
	
	
	function __construct($scope, $name, $args)
	{
		parent::__construct($scope, $name, $args);
		
		$this->groups = ( is_string($args['groups']) ? explode(' ', $args['groups']) : ((array) $args['groups']) );
		$this->props = $args['props'];
		
		
	}
	
	function setValue($value)
	{
		if(!\arr::isFirstAssoc($this->props))
		{
			$value = $this->parse_value(explode(' ', trim($value)));
		}
		
		foreach($this->groups as $i => $group_name)
		{
			if(!\arr::isFirstAssoc($this->props))
			{
				$this->__set($group_name, $value[$i]);
			}
			else {
				$this->__set($group_name, $value);
			}
		}
		
	}
	
	function __set($name, $value)
	{
		$name = str_replace('_', '-', $name);
	
		// set single property for all items
		if(\arr::isFirstAssoc($this->props) && isset($this->props[$name]))
		{
			if(is_string($value))
			{
				$value = explode(' ', trim($value));
			}
			
			if(\arr::isFirstAssoc($value))
			{
				$value = array_intersect_key($value, array_flip($this->groups));
				
				foreach($value as $group_name => $val)
				{
					$this->__get($group_name)->$name = $val;
				}
			}
			else {
				$value = $this->parse_value($value); // 1px 2px 3px 4px
				
				
				foreach((array)$this->groups as $i => $group_name)
				{
					if($value[$i] === Property::VALUE_REMOVE)
					{
						$this->__get($group_name)->$name = Property::VALUE_REMOVE;
					}
					else {
						$this->__get($group_name)->$name->setValue($value[$i]);
					}
				}
			}
			
		}
		// set all properties for single item
		elseif(in_array($name, $this->groups))
		{
			
			if($value === Property::VALUE_REMOVE)
			{
				unset($this->value[$name]);
			}
			else {
				$this->__get($name)->setValue($value); // #fff 1px solid
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
		
		if(in_array($prop_name, $this->groups))
		{
			if(\arr::isFirstAssoc($this->props))
			{
				$this->value[$prop_name] = new Group($this->style, "{$this->name}-{$prop_name}", $this->props);
			}
			else {
				
				$this->value[$prop_name] = Property::forge($this->style, "{$this->name}-{$prop_name}", $this->props); 
			}
//			debug("{$this->name}-{$prop_name}");
//			debug($this->value[$prop_name]);

			return $this->value[$prop_name];
		}
		
		return false;
	}

	
	private function parse_value($value) // 1px 2px 3px 4px
	{
		switch(count($value))
		{
			case 0:
				return;
			case 1:
				$value[1] = $value[0];
			case 2:
				$value[2] = $value[0];
			case 3:
				$value[3] = $value[1];
				break;
		}
		
		return $value;
	}
	
}

