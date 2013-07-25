<?php

namespace AJ\Template\Html\element;

use AJ\Misc\Arr;

class AttrClass implements \IteratorAggregate
{
	private $classNames = array();
	
	function getIterator()
	{
		return new \ArrayIterator($this->classNames);
	}

	function __construct($classNames = null)
	{	
		$this->add($classNames);
	}

	function all()
	{	
		return $this->classNames;
	}

	function has($className)
	{	
		return isset($this->classNames[$className]);
	}

	/**
	 * Get classes that exists
	 * @param mixed $classNames		[string|array] - 'class1 class2 ...'
	 * @return array			matched values
	 */
	function get($classNames)
	{
		return array_intersect_key($this->classNames, static::parseClassNames($classNames));
	}
	
	/**
	 * Add class
	 * @param mixed $classNames		[string|array] - 'class1 class2 ...'
	 * @return object self		$this
	 */
	function add($classNames)
	{
		//var_dump($classNames);
		$this->classNames = array_merge($this->classNames, static::parseClassNames($classNames));
		
		return $this;
	}

	function set($className)
	{
		$this->removeAll();
		$this->add($className);
	}

	/**
	 * Remove class
	 * @param mixed $classNames		[string|array] - 'class1 class2 ...'
	 * @return object self		$this
	 */
	function remove($classNames)
	{
		$this->classNames = array_diff_key($this->classNames, static::parseClassNames($classNames));

		return $this;
	}

	/**
	 * Remove all classes
	 * @return object self		$this
	 */
	function removeAll()
	{
		$this->classNames = array();

		return $this;
	}
	
	function render()
	{
		return trim(implode(' ', array_keys($this->classNames)));
	}
	


	function __toString()
	{
		return $this->render();
	}

	static private function parseClassNames($vars)
	{
		return array_flip(Arr::explode($vars)); 
	}
}

