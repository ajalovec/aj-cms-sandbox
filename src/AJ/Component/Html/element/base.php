<?php
namespace AJ\Template\Html\element;
/*
$text = Element::forge('input', array(
	'type' 	=> 'text',
	'name' 	=> '$field->key',
	'value' => '$field->value',
	'class'	=> array('asdasd','adasd')
));

$text->attr('type', 'text')
->attr('name', 'krneki')
->attr('test', 'krnasdaseki')
->attr('data-test', array('asdasd','adasd'))
->attrs(array(
	'type',
	'name' => 'krneki'
))
//->removeAttrs()

->addClass('input-medium datetimepicker')
->addClass('input-medium rdatetimepicker')
->removeClass('rdatetimepicker')
->addClass('test1 test2 test3')
->removeClass();

//debug($text->hasClass());
debug($text->render());
*/

abstract class Base {
	private static $READONLY_VARS = array(
		'uid', 'name', 'root', 'parent',
		//'class', 'attributes', 'children'
	);
	public $short_tag		= false;
	protected $uid;
	protected $name 		= 'div';
	
	protected $class 		= NULL;
	protected $attributes 	= NULL;
	protected $nodes			= array();
	protected $children		= array();
	
	protected $root			= NULL;
	protected $parent		= FALSE;
	

	
	static function forge($name = 'div', $attrs = array(), $value = array())
	{
		$ele = new static($name, $value);
		
		if(isset($attrs['class']))
		{
			$ele->addClass($attrs['class']);
			unset($attrs['class']);
		}
		
		$ele
		->attrs($attrs)
		->value($value);
		
		return $ele;
	}
	
	
	function __construct($name = 'div')
	{
		$this->short_tag 	= in_array($name[0], array('_', '/'));
		$this->name 		= $this->short_tag ? substr($name, 1) : $name;
		$this->root 		= $this;
		
		
		$this->attributes 	= new Attrs();
		$this->class 		= new AttrClass();
		
		$this->uid 			= spl_object_hash($this);
	}
	
	function getChild($offset)
	{
		if(isset($this->children[$offset])) {
			return $this->children[$offset];
		}
		
		return false;
	}
	
	function getChildren($offset = 0, $length = 0)
	{
		if(!$length) {
			return $this->children;
		}
		
		return array_splice($this->children, $offset, $length);
	}
		
	function hasChild($ele)
	{
		return in_array($ele, $this->children);
	}

	function isElement($value)
	{
		return ( $value instanceof self);
	}
	
/* ==========================================================
 *  Setters
** ==========================================================*/	
	function name($name)
	{
		$this->name = $name;
		return $this;
	}
	
	function setParent($parent)
	{
		if($this->isElement($parent))
		{
			$this->root 	= $parent->root;
			$this->parent 	= $parent;
		}
		
		return $this;
	}
	
	protected function addElement($name, $attrs, $value)
	{
		if($this->isElement($name))
		{
			$ele = $name;
			$name = $ele->name;
		}
		else {
			$ele = static::forge($name, $attrs, $value);
		}
		
		$ele->setParent($this);
		//debug($ele->uid);
		$this->nodes[$name][$ele->uid] = $ele;
		
		return $ele;
	}
	
	
	function append($name = 'div', $attrs = array(), $value = NULL)
	{
		$ele = $this->addElement($name, $attrs, $value);
		
		$this->children[] = $ele;
		
		return $ele;
	}
	
	function prepend($name = 'div', $attrs = array(), $value = NULL)
	{
		$ele = $this->addElement($name, $attrs, $value);
		array_unshift($this->children, $ele);
		
		return $ele;
	}
	
	function remove($children)
	{
		$this->nodes 	= array();
		$this->children = array();
		
		return $this;
	}
	
	function value($value = FALSE)
	{
		if($value)
		{
			$this->children = is_string($value) ? array($value) : (array) $value;
		}
		else {
			$this->children = array();
		}
		
		return $this;
	}
	
	function text($text)
	{
		$this->appendText($text);
		return $this;
	}
	
	function appendText($text)
	{
		$this->children[] = Text::forge($text);
		return $this;
	}
	
	function prependText($text)
	{
		array_unshift($this->children, Text::forge($text));
		return $this;
	}
	
	
/* ==========================================================
 *  Attributes
** ==========================================================*/	
	function attr($key, $value = NULL)
	{
		if(is_int($key))
		{
			$key = $value;
			$value = false;
		}
		switch (func_num_args())
		{
			case 0:
				return $this->attributes;
			case 1:
				return $this->attributes->get($key);
			
			default:
				$this->attributes->set($key, $value);
		}
		return $this;
	}
	
	function attrs($array)
	{
		array_map(array($this, 'attr'), array_keys($array), $array);
		
		return $this;
	}
	
	function removeAttrs($keys = NULL)
	{
		$this->attributes->remove($keys);
		return $this;
	}
	
	
	function hasClass($values = NULL)
	{
		$result = $this->class->get($values);
		return empty($result) ? FALSE : $result;
	}
	
	function addClass($values)
	{
		$this->class->add($values);
		return $this;
	}
	
	function removeClass($values = NULL)
	{
		$this->class->remove($values);
		return $this;
	}
	
	
/* ==========================================================
 *  Render
** ==========================================================*/	
	function render()
	{
		$br = (is_string($this->children[0]) or count($this->children) == 0) ? "" : "\n";
		
		$output = '<' . $this->name;
		
		if($attr_class = $this->class->render())
		{
			$this->attributes->set('class', $attr_class);
			//$output .= ' class="' . $attr_class .'"';
		}
		
		if($attrs = $this->attributes->render())
		{
			$output .= ' ' . $attrs;
		}
		
		if($this->short_tag)
		{
			$output .= ' />';
			$output .= "\n";
		}
		else {
			$output .= '>';
			$output .= $br;
			
			$output .= (string) implode("", $this->children);
			
			$output .= $br;
			$output .= '</' . $this->name . ">\n";
			
		}
		
		return $output;
	}
	
	function __toString()
	{
		return (string) $this->render();
	}
	
	
	function __get($key)
	{
		if(in_array($key, self::$READONLY_VARS))
		{
			return $this->$key;
		}
		
		return null;
	}
	
}

