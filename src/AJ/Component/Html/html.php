<?php

namespace AJ\Template\Html;

use AJ\Template\Html\Element;

class Html {
	
	
	static function img($src, $alt='', $attrs = array())
	{
		$attrs['src'] = $src;
		$attrs['alt'] = $alt;
		
		return static::element('_img', $attrs);
	}
	
	static function div($class=NULL, $attrs = array())
	{
		if(is_array($class)) {
			$attrs = $class;
		}
		else {
			$attrs['class'] = $class;
		}
		
		return static::element('div', $attrs);
	}
	
	
	static function element($name = 'div', $attrs = array(), $value = NULL)
	{
		return Element::forge($name, $attrs, $value);
	}
	
	static function __callstatic($name, $args = array())
	{
		$attrs = isset($args[0]) ? $args[0] : array();
		$value = isset($args[1]) ? $args[1] : null;
		
		return Element::forge($name, $attrs, $value);
	}
	
	
}
