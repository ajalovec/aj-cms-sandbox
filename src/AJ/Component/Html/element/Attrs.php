<?php

namespace AJ\Template\Html\element;

use AJ\Misc\Arr;

class Attrs implements \IteratorAggregate
{
	private $_test = 'krneki';
	private $attributes	= array();
	
	function __construct($attributes = array())
	{
		$this->add($attributes);
    }

	function getIterator()
	{
        return new \ArrayIterator($this->attributes);
    }

	function all()
	{
        return $this->attributes;
    }

	function has($attributeName)
	{
        return isset($this->attributes[$attributeName]);
    }
    static private $attributeTypes = array(
    	'class' 		=> "AttrClass",
    	'data-class'	=> "AttrClass",
    );
	/**
	 * Get attribute
	 * 
	 * @param string $key		attribute name
	 * @return mixed value		če ne obstaja vrne NULL
	 */
	private function newAttributeClass($attributeName, $value = null)
	{
		$attributeClass = 'AJ\\Template\\Html\\element\\' . static::$attributeTypes[$attributeName];

		return new $attributeClass($value);
	}

	function get($attributeName)
	{
		if(isset($this->attributes[$attributeName]))
		{
			return $this->attributes[$attributeName];
		}

		if(isset(static::$attributeTypes[$attributeName]))
		{
			return $this->attributes[$attributeName] = $this->newAttributeClass($attributeName);
		}

		return null;
	}

	/**
	 * Set attribute value
	 * Če je value NULL zbriše attribute
	 * @param string 	$key		atribute name
	 * @param mixed  	$value		[null - zbriše], [array|object - implode vrednosti], [true|false - 'true'|'false']
	 * @return object self			$this
	 */
	function set($attributeName, $value)
	{
		if(NULL === $value) {
			unset($this->attributes[$attributeName]);
			return;
		}

		if(isset(static::$attributeTypes[$attributeName]))
		{
			if(isset($this->attributes[$attributeName]))
			{
				$value = $this->attributes[$attributeName]->add($value);
			}
			else {
				$value = $this->newAttributeClass($attributeName, $value);
			}
		}

		return $this->attributes[$attributeName] = $value;
	}


	/**
	 * Add multiple attributes
	 * Če je value NULL zbriše vse atribute
	 * @param mixed 	$attrs		array, objekt ali query string attributov
	 * @param boolean  	$replace	če je false ne zamenja enakih attributov
	 * @return object self			$this
	 */
	function add($attributes)
	{
		$attributes = static::parseAttrs($attributes);
		//debug($attributes);
		foreach ($attributes as $attributeName => $attributeValue)
		{
			$this->set($attributeName, $attributeValue);
		}
		
		//return $this;
	}

	
	
	/**
	 * Remove class
	 * @param mixed $values		[string|array] - 'class1 class2 ...'
	 * @return object self		$this
	 */
	function remove($attributeNames)
	{
		$attributeNames = static::parseNames($attributeNames);

		$this->attributes = array_diff_key($this->attributes, $attributeNames);
		
		return $this;
	}

	function removeAll()
	{
		$this->attributes = array();
	}
	

	/**
	 * Add multiple attributes
	 * Če je value NULL zbriše vse atribute
	 * @param mixed 	$attrs		array, objekt ali query string attributov
	 * @param boolean  	$replace	če je false ne zamenja enakih attributov
	 * @return object self			$this
	 */
	function required($attributeNames)
	{
		$attributeNames = is_array($attributeNames) ? $attributeNames : explode(' ', $attributeNames);
		//debug(get_class($value));
		foreach ($attributeNames as $key)
		{
			if(!isset($this->$key)) $this->$key = "";
		}
		
		return $this;
	}


	function render()
	{
		$output = '';
		foreach($this->attributes as $key => $value)
		{
			$output .= $key . '="' . htmlspecialchars((string) $value) . '" ';
		}
		
		return trim($output);
	}
	
	
	/* ==========================================================================
	 * Magic methods - Getter Setter
	 */
	function __toString()
	{
		return $this->render();
	}

	function __isset($attributeName)
	{
		return $this->has($attributeName);
	}
	
	function __get($attributeName)
	{
		return $this->get($attributeName);
	}
	
	function __set($attributeName, $value)
	{
		return $this->set($attributeName, $value);
	}
	
	function __unset($attributeName)
	{
		return $this->remove($attributeName);
	}
	
	

	/* ==========================================================================
	 * static helpers
	 */
	static function parseNames($vars)
	{
		return array_flip(Arr::explode($vars)); 
	}

	static function parseAttrs($attrs)
	{
		if(is_array($attrs) || $attrs instanceof \stdClass)
		{
			return (array) $attrs;
		}
		//$attrs = urlencode($attrs);

		parse_str($attrs, $attrs);
		
		return (array) $attrs;
	}


	static function forge($attrs = array())
	{
		$object = new Attrs();

		$object->add($attrs);

		return $object;
	}

}

/*

class Attrs {
	
	/**
	 * Set attribute value
	 * Če je value NULL zbriše attribute
	 * @param string 	$key		atribute name
	 * @param mixed  	$value		[null - zbriše], [array|object - implode vrednosti], [true|false - 'true'|'false']
	 * @return object self			$this
	 *-/
	function set($key, $value)
	{
		if($value === NULL)
		{
			unset($this->$key);
			return;
		}
		
		if(is_array($value) || is_object($value))
		{
			$value = implode(' ', (array)$value);
		}
		
		$this->$key = $value;
		
		return $this;
	}
	/**
	 * Get attribute
	 * 
	 * @param string $key		attribute name
	 * @return mixed value		če ne obstaja vrne NULL
	 *-/
	function get($key)
	{
		if(isset($this->$key))
		{
			return $this->$key;
		}
		
		return NULL;
	}
	
	function render()
	{
		$output = '';
		foreach($this as $key => $value)
		{
			$output .= $key . '="' . $value . '" ';
		}
		
		return trim($output);
	}
	
}
*/
