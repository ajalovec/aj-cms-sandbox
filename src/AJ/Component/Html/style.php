<?php

namespace AJ\Template\Html;


use AJ\Template\Html\style\Property;
/*
$test = AJ\Template\Html::div(array(
	'class' => 'body'
), 'body');

//$test->style->derek = 'absolute';
$test->style->margin->top = '107px';

$test->style->border = '#fff 0px solid';
$test->style->border->width = '1px 42px 1000px';
$test->style->border->top = '#ff0000 99% dotted';
//$test->style->border->top->width->setValue(false);
//$test->style->border->style = 'dotted';

//$test->style->border->top = array('color'=>"#f00");
//$test->style->border->top->width = '91%';
//debug((bool)array());
//$test->style->border->top->color = '#fff';
//$test->style->border = '#fff 1px solid';
$test->style->width = '100px';

$test->style->position = 'absolute';
$test->style->height	= '50px';
$test->style->background = '#fff';
//echo (float) "15%";
//echo (string) "15px";
//var_dump((float) "auto%");

//debug($test->style->render());
*/

class Style  {
	private $separator = array('newLine' => "\n", 'lineStart' => "\t");
	private $prop = array();
	protected $element = array();
	
	function __construct($element)
	{
		$this->separator = (object) $this->separator;
		$this->element = $element;
	}
	
	
	function __set($prop_name, $value)
	{
		$prop_name = str_replace('_', '-', $prop_name);
		
		if($prop = $this->__get($prop_name))
		{
			if($value === Property::VALUE_REMOVE)
			{
				unset($this->prop[$prop_name]);
			}
			else {
				
				$prop->setValue($value);
			}
		}
	}
	
	function __get($prop_name)
	{
		$prop_name = static::sanitize_propname($prop_name);
		
		if(is_object($this->prop[$prop_name]))
		{
			return $this->prop[$prop_name];
		}
		
		switch($prop_name)
		{
			case 'width':
			case 'height':
				$result = Property::forge($this, $prop_name, Property::$cfg['size']);
				break;
			default:
				if(Property::$cfg[$prop_name]) {
					$result = Property::forge($this, $prop_name, Property::$cfg[$prop_name]);
				}
				else {
					$result = Property::forge($this, $prop_name);
				}
		}
		
		$this->prop[$prop_name] = $result;
		
		return $result;
	}

	function fromArray($arr)
	{
		
	}
	
	function toCss($selector = "", $string = null)
	{
		if(!is_string($string))
		{
			$string = $this->toString();
		}
		
		return static::wrapStyle($selector, $string);
	}
	
	
	function toString($name = '', $arr = null)
	{
		if(!is_array($arr))
		{
			$arr = $this->toArray();
		}
		
		$output = '';
		
		foreach($arr as $prop_name => $value)
		{
			$full_name = (strlen($name) ? "$name-$prop_name" : $prop_name);
			
			if(is_array($value))
			{
				$output .= $this->toString($full_name, $value);
			}
			else {
				$output .= $this->separator->lineStart . "{$full_name}: {$value};" . $this->separator->newLine;
			}
			
		}
		
		return $output;
	}
	
	function toArray($as_object = false)
	{
		$arr = array();
		
		foreach($this->prop as $prop_name => $prop)
		{
			$arr[$prop_name] = $prop->getValue($as_object);
		}
		//debug(array_keys($this->prop));
		
		return $as_object ? ((object) $arr) : $arr;
	}
	
	function toObject()
	{
		return $this->toArray(true);
	}
	
	
	function __toString()
	{
		return (string) $this->toString();
	}
	
	
	
	static function wrapStyle($selector, $style)
	{
		//if(!$style) return '';
		
		$output = "{$selector} {\n";
		
		$output .= $style;
		
		$output .= "}\n";
		
		return $output;
	}
	
	static function sanitize_propname($name)
	{
		return str_replace('_', '-', $name);
	}
	
}

