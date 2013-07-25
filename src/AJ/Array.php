<?php

namespace AJ;

class Array {
	
	static function explode($data, $delimiter = ' ')
	{
		if(is_string($data))
		{
			switch($delimiter)
			{
				case ' ':
					$data = explode(' ', str_replace('  ', ' ', $data));
					break;
			}
		}
	    return (array) $data;
	}
	
	static function isAssoc(array $data)
	{
	    return array_keys($data) !== range(0, count($data) - 1);
	}
	
	static function isFirstAssoc(array $data)
	{
	    reset($data);
		return is_string(key($data));
	}
		
	
	static function each(array $data, $callback = null) {
		foreach($data as $k => $v)
		{
			$callback($k, $v);
		}
	}
	
	/**
	 * Gets a dot-notated key from an array, with a default value if it does
	 * not exist.
	 *
	 * @param   array   $array    The search array
	 * @param   mixed   $key      The dot-notated key or array of keys
	 * @param   string  $default  The default value
	 * @return  mixed
	 */
	static function get($array, $key, $default = null, $separator = '.')
	{
		if ( ! is_array($array))
		{
			debug('First parameter must be an array or ArrayAccess object.');
		}

		if (is_null($key))
		{
			return $array;
		}

		if (is_array($key))
		{
			$return = array();
			foreach ($key as $k)
			{
				$return[$k] = static::get($array, $k, $default);
			}
			return $return;
		}

		foreach (explode($separator, $key) as $key_part)
		{
			if ((isset($array[$key_part])) === false)
			{
				if ( ! is_array($array) or ! array_key_exists($key_part, $array))
				{
					return $default;
				}
			}

			$array = $array[$key_part];
		}

		return $array;
	}
	
	static function addBefore(&$array, $key, $value = null)
	{
		/* 
		$input = array("red", "green", "blue", "yellow");
		array_splice($input, -1, 1, array("black", "maroon"));
		// $input is now array("red", "green", "blue", "black", "maroon")
		 
		$input = array("red", "green", "blue", "yellow");
		array_splice($input, 3, 0, "purple");
		// $input is now array("red", "green", "blue", "purple", "yellow");
		*/
	}
	
	
	/**
	 * Set an array item (dot-notated) to the value.
	 *
	 * @param   array   $array  The array to insert it into
	 * @param   mixed   $key    The dot-notated key to set or array of keys
	 * @param   mixed   $value  The value
	 * @return  void
	 */
	static function set(&$array, $key, $value = null, $mergeArray = FALSE, $separator = '.')
	{
		if (is_null($key))
		{
			if($mergeArray > 0 && is_array($value))
			{
				$array = $mergeArray == 1 ? array_replace_recursive($array, $value):array_merge_recursive($array, $value);
			}
			else
			{
				$array = $value;
			}
				
			return;
		}

		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				static::set($array, $k, $v);
			}
		}

		$keys = explode($separator, $key);

		while (count($keys) > 1)
		{
			$key = array_shift($keys);
			
			if (! isset($array[$key]) or ! is_array($array[$key]))
			{
				if($key === '@')
				{
					$array[] = array();
					end($array);
					$key = key($array);
				}
				else
					$array[$key] = array();
			}
			
			$array = & $array[$key];
		}
		
		if($mergeArray && is_array($value))
		{
			$newKey = array_shift($keys);
			$array[$newKey] = $mergeArray == 1 ? array_replace_recursive($array[$newKey], $value):array_merge_recursive($array[$newKey], $value);
		}
		else
		{
			$newKey = array_shift($keys);
			
			if($newKey === '@')
				$array[] = $value;
			else
				$array[$newKey] = $value;
		}
	}
	
	/**
	 * Array_key_exists with a dot-notated key from an array.
	 *
	 * @param   array   $array    The search array
	 * @param   mixed   $key      The dot-notated key or array of keys
	 * @return  mixed
	 */
	static function key_exists($array, $key)
	{
		foreach (explode('.', $key) as $key_part)
		{
			if ( ! is_array($array) or ! array_key_exists($key_part, $array))
			{
				return false;
			}

			$array = $array[$key_part];
		}

		return true;
	}

	/**
	 * Unsets dot-notated key from an array
	 *
	 * @param   array   $array    The search array
	 * @param   mixed   $key      The dot-notated key or array of keys
	 * @return  mixed
	 */
	static function unset_key(&$array, $key)
	{
		$removed = false;
		if (is_null($key))
		{
			return $removed;
		}

		if (is_array($key))
		{
			$return = array();
			foreach ($key as $k)
			{
				$return[$k] = static::delete($array, $k);
			}
			return $return;
		}

		$key_parts = explode('.', $key);

		if ( ! is_array($array) or ! array_key_exists($key_parts[0], $array))
		{
			return $removed;
		}

		$this_key = array_shift($key_parts);

		if ( ! empty($key_parts))
		{
			$key = implode('.', $key_parts);
			return static::delete($array[$this_key], $key);
		}
		else
		{
			$removed = $array[$this_key];
			unset($array[$this_key]);
		}

		return $removed;
	}
	
	static function unset_keys(&$array, $keys)
	{
		if(is_string($keys))
		{
			$keys = explode(',', str_replace(' ','',trim($keys)));
		}
		$removed = array();
		
		foreach($keys as $key)
		{
			$removed[$key] = $array[$key];
			unset($array[$key]);
		}
		
		return $removed;
	}
	
		
		
	/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Convert array to human string
	* 
	* @param	array		source array
	* @param	string		pre-indent string
	* @return	string		humanized array
	*/
	static function humanize($array, $preindent = "") {
		if(!is_array($array)) return false;
		$result = "array(\n";
		$indent_line = $preindent."	";
		$i = 1;
		$len = count($array);
		foreach($array as $key => $value)
		{
			$indent_separator = is_array($value) ? " ":"			";
			// key and separator
			if(is_string($key))
			{
				$result .= $indent_line."'{$key}'{$indent_separator}=> ";
			}
			else
			{
				$result .= $indent_line;
			}
			// pogleda tip vrednosti
			if(is_array($value)) {
				$result .= self::humanize($value, $indent_line);
			}
			elseif(is_object($value)) {
				$result .= self::humanize((array)$value, $indent_line);
			}
			elseif(strlen($value) > 0)
			{
				$result .= is_numeric($value) ? $value:"'{$value}'";
			}
			elseif($value === NULL)
			{
				$result .= 'NULL';
			}
			elseif($value == FALSE)
			{
				$result .= 'FALSE';
			}
			elseif($value === TRUE)
			{
				$result .= 'TRUE';
			}
			else
			{
				$result .= $value;
			}
			
			
			if($i == $len)
			{
				$result .= "\n";
			}
			else
			{
				$result .= ",\n";
			}
			
			++$i;
		}
		
		$result .= $preindent.")";
		
		return $result;
	}
	
	
	/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Replace array key with template
	* 
	* @param	array		source array
	* @param	string		template rule [~ = key]
	* @param	boolean		replace recursive
	* @return	array		array with new key
	*/
	static function edit_key($array, $rule = '~', $recursive = false)
	{
		$result = array();
		
		foreach($array as $key=>$val)
		{
			// populate keys
			$newKey = str_replace("~", $key, $rule);
			
			if($recursive && is_array($val))
			{
				$result[$newKey] = self::edit_key($val, $rule, true);
			}
			else
			{
				$result[$newKey] = $val;
			}
			
		}
		return $result;
	}
	
	/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Replace array string values with template
	* 
	* @param	array		source array
	* @param	string		template rule [~ = value]
	* @param	boolean		replace recursive
	* @return	array		array with new values
	*/
	static function edit_value($array, $rule = '~', $recursive = false)
	{
		$result = array();
		
		foreach($array as $key=>$val)
		{
			// populate keys
			if($recursive && is_array($val))
			{
				$result[$key] = self::edit_value($val, $rule, true);
			}
			else
			{
				$newValue = str_replace("~", $val, $rule);
				$result[$key] = $newValue;
			}
			
		}
		return $result;
	}
		
	
	
	
	
	
}
