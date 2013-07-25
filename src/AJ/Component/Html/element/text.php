<?php
namespace AJ\Template\Html\element;
/*
$text = Element::forge('input', array(
	'type' 	=> 'text',
	'name' 	=> '$field->key',
	'value' => '$field->value',
	'class'	=> array('asdasd','adasd')
));


//debug($text->hasClass());
debug($text->render());
*/



class Text {
	protected $value		= "";
	
	static function forge($value = '')
	{
		$text = new self($value);
		
		return $text;
	}
	
	static function getSelf()
	{
		return self;
	}
	
	function __construct($value = '')
	{
		$this->value = $value;
	}
	
	
	function value($value = '')
	{
		$this->value = (string) $value;
	}
	
	function append($value = '')
	{
		$this->value .= (string) $value;
	}
	
	function prepend($value = '')
	{
		$this->value = ( (string) $value . $this->value );
	}
	
/* ==========================================================
 *  Render
** ==========================================================*/	
	function render()
	{
		return $this->value;
	}
	
	function __toString()
	{
		return (string) $this->render();
	}
	
	
	function __get($key)
	{
		//return $this->$key;
	}
	
}

