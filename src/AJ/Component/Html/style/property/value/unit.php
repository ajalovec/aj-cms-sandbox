<?php

namespace AJ\Template\Html\style\property\value;

require_once 'num.php';

class Unit extends Num {
	static private $units = array(
		'%',
		'px',	// pixels (a dot on the computer screen)
		'cm',
		'mm',
		'in',	// inch
		'pt',	// point (1pt = 1/72 inch)
		'pc',	// pica (1pc = 12 points)
		'em',	// 1em is equal to the current font size. 2em means 2 times the size of the current font. E.g., if an element is displayed with a font of 12 pt, then '2em' is 24 pt. The 'em' is a very useful unit in CSS, since it can adapt automatically to the font that the reader uses
		'ex',	// one ex is the x-height of a font (x-height is usually about half the font-size)
	);
	protected $unit = 'px';
	
	
	function setValue($value)
	{
		parent::setValue($value);
		
		if($this->type === static::TYPE_NUM)
		{
			$unit = str_replace($this->value, '', $value);
			if(in_array($unit, static::$units))
			{
				$this->unit = $unit;
			}
		}
		
	}
	
	function getValue()
	{
		//debug("$this->type: $this->value");
		switch($this->type)
		{
			case static::TYPE_BASE:
			case static::TYPE_ZERO:
			case static::TYPE_DEFAULT:
				return $this->value;
			case static::TYPE_NUM:
				return $this->value . $this->unit;
		}
		
	}
	
	
}

