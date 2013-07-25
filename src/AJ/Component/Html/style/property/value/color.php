<?php

namespace AJ\Template\Html\style\property\value;



class Color extends Base {
	const TYPE_HEX 		= '#';
	const TYPE_RGB 		= 'rgb';
	
	static private $types = array(
		'rgb',	// rgb(255, 0, 0)
		'#',	// #ff0000
	);
	
	function setValue($value)
	{
		if(!parent::setValue($value))
		{
			if($p = strpos('(', $value))
			{
				$this->type = substr($value, 0, $p);
				$this->value = substr($value, ($p+1), -1);
			}
			elseif($value[0] == '#')
			{
				$this->value = substr($value, 1);
				
				if(strlen($this->value) == 3)
				{
					$this->value .= $this->value;
				}
				
				if(strlen($this->value) == 6)
				{
					$this->type = static::TYPE_HEX;
				}
				else {
					$this->type = static::TYPE_DEFAULT;
					$this->value = $this->default;
				}
			}
			
		}
	}
	
	function getValue()
	{
		switch($this->type)
		{
			case static::TYPE_BASE:
			case static::TYPE_DEFAULT:
				return $this->value;
			case static::TYPE_HEX:
				return static::TYPE_HEX . $this->value;
			case static::TYPE_RGB:
				return static::TYPE_RGB . "({$this->value})";
		}
	}
}

