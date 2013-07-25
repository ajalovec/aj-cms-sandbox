<?php

namespace AJ\Template\Html\style\property\value;



class Num extends Base {
	const TYPE_DEFAULT 	= 'default';
	const TYPE_ZERO 	= 'zero';
	const TYPE_NUM 		= 'number';
	
	
	function setValue($value)
	{
		
		if($value === 0 || $value === '0')
		{
			$this->type = static::TYPE_ZERO;
			$this->value = 0;
		}
		else {
			$float_value = (float) $value;
			// pogleda če je različno od 0 kar pomeni da je številka
			
			if($float_value != 0)
			{
				$this->type = static::TYPE_NUM;
				
				if(false || is_int($value) || is_float($value))
				{
					$this->value += $float_value;
				}
				else {
					$this->value = $float_value;
				}
				/*
				switch($value[0])
				{
					case '-':
					case '+':
						if(is_string($value))
						{
							$this->value += $float_value;
							break;
						}
					default:
						$this->value = $float_value;
				}
				*/
			}
			// pogleda če obstaja kot argument drugače naponlni defaultno vrednost
			elseif(!parent::setValue($value)) {
				$this->type = static::TYPE_DEFAULT;
				$this->value = $this->default;
			}
			
		}
	}
	
	function getValue()
	{
		
		switch($this->type)
		{
			case static::TYPE_BASE:
			case static::TYPE_ZERO:
			case static::TYPE_NUM:
			case static::TYPE_DEFAULT:
				return $this->value;
		}
		
	}
	
	
}

