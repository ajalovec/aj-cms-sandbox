<?php

namespace AJ\Template\Html;

require_once 'style/property.php';

use AJ\Template\Html\style\Property;
/*
$test = \html::div(array(
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

class Css extends Style  {
	static private $CSS_PREFIX = array('css' => ':', 'class' => '.');
	private $style = array();
	
	
	function __get($prop_name)
	{
		list($prefix, $name) = explode('_', $prop_name, 2);
		
		// css selector - :hover :active :first-line ... 
		if($name && isset(static::$CSS_PREFIX[$prefix]))
		{
			$prop_name = static::$CSS_PREFIX[$prefix] . static::sanitize_propname($name);
			
			if($this->style[$prop_name] instanceof Style)
			{
				return $this->style[$prop_name];
			}
			
			$this->style[$prop_name] = new Style($this->element);
			//debug($prop_name);
			
			return $this->style[$prop_name] = new Style($this->element);
		}
		
		// style property - border margin font-size ...
		return parent::__get($prop_name);
		
	}
	
	function render($selector = "")
	{
		$output = $this->toCss($selector);
		
		foreach($this->style as $style_selector => $style)
		{
			$subselector = $selector . $style_selector;
			$output .= $style->toCss($subselector);
		}
		
		return $output;
	}
	
	
	
}

