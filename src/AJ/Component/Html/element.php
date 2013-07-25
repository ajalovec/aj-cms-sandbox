<?php
namespace AJ\Template\Html;

use AJ\Template\Html\element\Base as BaseElement;

/*
Element::h1(array('style' => 'color:red', 'class'=>'jajajaja ajsdasd'))
->text("juhuhuh")
->removeClass('ajsdasd');

$doc = Element::div(array('style' => 'border:red 1px solid;min-height:20px;'), 'adasdasdasads');
$doc->append($text)->append($text);
$doc->render();
*/


class Element extends BaseElement {
	private static $READONLY_VARS = array('style');
	protected $style	 		= NULL;
	
	function __construct($name = 'div')
	{
		parent::__construct($name);
		
		$this->style 		= new Css($this);
		
	}
	
	function tag($name)
	{
		return reset($this->nodes[$name]);
	}
	
	
	function renderCss($selector_prefix = false)
	{
		$selector = ($selector_prefix ? ($selector_prefix . ' ') : '') . $this->name;
		
		$output = $this->style->render($selector);
		
		
		foreach ($this->getChildren() as $child)
		{
			if(!method_exists($child, 'renderCss')) continue;
			
			$output .= $child->renderCss($selector);
		}
		
		return $output;
	}
	
	
	function __get($key)
	{
		if(substr($key, 0, 2) == 'tag_')
		{
			return $this->tag(substr($key, 2));
		}
		
		$val = parent::__get($key);
		
		if($val === NULL && in_array($key, self::$READONLY_VARS))
		{
			return $this->$key;
		}
		
		return $val;
	}
	
	
}

