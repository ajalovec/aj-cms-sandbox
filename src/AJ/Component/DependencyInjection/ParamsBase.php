<?php

namespace AJ\Component\DependencyInjection;


class ParamsBase {
	const EXPORT_ALL	 	= 1;
	const EXPORT_SCALAR	 	= 2;
	const TYPE_JSON 		= 'JSON';
	const TYPE_STRING 		= 'String';
	const TYPE_ARRAY 		= 'Array';
	
	private $parent;
	private $stack = array();
	
	
	function __construct($parent, $stack = array())
	{
		$this->parent = $parent;
		$this->add($stack);
	}

	function parent()
	{
		return $this->parent;
	}
	
	function length()
	{
		return count($this->stack);
	}
	
	function getKeys()
	{
		return array_keys($this->stack);
	}
	
	function getArray($name = null, $default = array())
	{
		$obj = $this->get($name);
		
		if($obj instanceof self)
		{
			return $obj->toArray();
		}
		
		return $default;
	}
	
	function getJSON($name = null, $default = '')
	{
		$obj = $this->get($name);
		
		if($obj instanceof self)
		{
			return $obj->toJSON();
		}
		
		return $default;
	}
	
	function getString($name = null, $default = "")
	{
		$obj = $this->get($name);
		
		if($obj instanceof self)
		{
			return $obj->toString();
		}
		
		return $default;
	}
	
	function has($name = null)
	{
		return (boolean) $this->get($name);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param $name		param_name | param1.param2 | param1.
	 * @return Params object
	 */
	function get($name = null, $default = null)
	{
		if($name || $name === 0)
		{
			$name = explode('.', $name, 2);
			
			if($this->__isset($name[0]))
			{
				$param = $this->__get($name[0]);
				
				if(isset($name[1]))
				{
					if($param instanceof self)
					{
						return $param->get($name[1], $default);
					}
					
					return $default;
				}
				
//				if($param instanceof self and !$param->length())
//				{
//					return false;
//				}
				
				return $param;
				
			}
			
			return $default;
		}
		
		return $this;
	}
	
	function set($name, $value)
	{
		if($name || $name === 0)
		{
			@list($key, $name) = explode('.', $name, 2);
			
			if(is_string($name) && strlen($name) > 0)
			{
				if(!($this->__get($key) instanceof self))
				{
					$this->__unset($key);
				}
				
				$this->__get($key)->set($name, $value);
				
			}
			else {
				$this->__set($key, $value);
			}
		}
		
		return $this;
	}
	
	function add($array)
	{
		if($array instanceof self)
		{
			$array = $array->toArray();
		}
		
		foreach((array) $array as $key => $value)
		{
			$this->__set($key, $value);
		}
		
		return $this;
	}
	
	
	function addJSON($string)
	{
		$this->add(json_decode($string));
		
		return $this;
	}
	
	function addString($string)
	{
		parse_str($string, $arr);
		
		$this->add($arr);
		
		return $this;
	}
	
	function remove($keys = null)
	{
		$result = array();
		
		if($keys === null)
		{
			$result = $this->stack;
			
			$this->stack = array();
			
			return $result;
		}
		
		
		if(is_string($keys))
		{
			$keys = explode(' ', $keys);
		}
		
		foreach((array) $keys as $key)
		{
			if($val = $this->__unset($key))
			{
				$result[$key] = $val;
			}
		}
		
		return $result;
	}
	
	
	function toArray($exportFilter = null)
	{
		$result = array();
		foreach($this->stack as $key => $param)
		{
			if($param instanceof self || (is_object($param) && method_exists($param, 'toArray')))
			{
				$result[$key] = $param->toArray();
			}
			elseif(is_scalar($param) || is_null($exportFilter)) {
				$result[$key] = $param;
			}
			elseif($exportFilter === static::EXPORT_SCALAR) {
				$result[$key] = (array) $param;
			}
		}
		if($exportFilter === static::EXPORT_SCALAR) {
			$result = json_encode($result);
			$result = json_decode($result, true);
		}
		
		return $result;
	}
	
	function toJSON()
	{
		$output = json_encode($this->toArray());
		
		return $output;
	}
	
	function toString()
	{
		return http_build_query($this->toArray());
	}
	
	
	
	
	
	function __get($key)
	{
		//$key = strtolower($key);
		
		if(isset($this->stack[$key]))
		{
			return $this->stack[$key];
		}
		
		return $this->stack[$key] = new ParamsBase($this);
	}
	
	function __set($key, $value)
	{
		//$key = strtolower($key);
		if(is_array($value) or $value instanceof \stdClass)
		//if(is_array($value) or (is_object($value) and !($value instanceof self)))
		{
			foreach((array) $value as $arr_key => $arr_value)
			{
				$arr_key = is_string($arr_key) ? str_replace("\0", '', $arr_key) : $arr_key;
				
				$this->__get($key)->__set($arr_key, $arr_value);
			}
		}
		else {
			$this->stack[$key] = $value === null ? false : $value;
		}
		
	}
	
	function __unset($key)
	{
		//$key = strtolower($key);
		
		if(isset($this->stack[$key]))
		{
			$val = $this->stack[$key];
			unset($this->stack[$key]);
			return $val;
		}
		
	}
	
	function __isset($key)
	{
		//$key = strtolower($key);
		
		return (isset($this->stack[$key]) );//or ($this->length() != 0));
	}
	
 	function __invoke($type = null)
    {
    	$method_name = 'to' . $type;
    	if(method_exists($this, $method_name))
    	{
    		return $this->$method_name();
    	}
        return "aaaaa {$type}";
    }
    
	function __toString()
	{
		return serialize($this);
	}
	
	
	

	
}


