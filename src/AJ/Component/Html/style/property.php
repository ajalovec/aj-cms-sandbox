<?php

namespace AJ\Template\Html\style;

class Property   {
	const VALUE_REMOVE = NULL;
	const NS = '\\AJ\Template\Html\\style\\property\\';
	static $types = array('unit', 'num', 'color', 'url', 'position', 'custom');
	static $cfg = array(
		// dimensions
		'size' 			=> array('value\\Unit', 'auto'),
		'min_size' 		=> array('value\\Unit', '0'),
		'max_size' 		=> array('value\\Unit', 'none'),
		// positioning
		'margin' => array(
			'Repeat',
			array(
				'groups' => 'top right bottom left',
				'props' => array('value\\Unit', '0 auto')
			)
		),
		'padding' => array(
			'Repeat',
			array(
				'groups' => 'top right bottom left',
				'props' => array('value\\Unit', '0')
			)
		),
		'position' 		=> array('Value', 'static relative absolute fixed'),
		'offset'		=> array('value\\Unit', 'auto'),	// top right bottom left
		'z-index' 		=> array('value\\Num', 'auto'),
		// display
		'cursor' 		=> array('Value', 'auto default pointer text wait progress help move crosshair n-resize e-resize s-resize w-resize nw-resize ne-resize se-resize sw-resize'),
		'display' 		=> array('Value', 'inline block inline-block none'),
		'visibility' 	=> array('Value', 'visible hidden collapse'),
		'overflow'		=> array('Value', 'visible hidden scroll auto'),
		'clip',
		'float' 		=> array('Value', 'none left right'),
		'clear' 		=> array('Value', 'none left right both'),
		// border
		'border' => array(
			'Repeat',
			array(
				'groups' => 'top right bottom left',
				'props' => array(
					'color'	=> array('value\\Color', 'transparent'),
					'width'	=> array('value\\Unit', 'medium thin thick'),
					'style'	=> array('Value', 'none solid dotted dashed double groove ridge inset outset'),
				)
			)
		),
		// background
		'background' => array(
			'Group',
			array(
				'color'			=> array('value\\Color', 'transparent'),
				'image'			=> array('value\\Url', 'none'),
				'repeat'		=> array('Value', 'repeat repeat-x repeat-y no-repeat'),
				'position'		=> array('Value', 'left right top bottom center'),
				'attachment'	=> array('Value', 'scroll fixed'),
			)
		),
		// font
		'line-height'	=> array('value\\Unit', 'none'),
		'font' => array(
			'Group',
			array(
				'size'			=> array('value\\Unit', 'medium small x-small xx-small large x-large xx-large smaler larger'),
				'weight'		=> array('Value', 'normal bold bolder lighter 100 200 300 400 500 600 700 800 900'),
				'family'		=> array('value\\Custom', ''),
				'style'			=> array('Value', 'normal italic oblique'),
				'variant'		=> array('Value', 'normal small-caps'),
			)
		),
		// text
		'text' => array(
			'Group',
			array(
				'align'			=> array('Value', 'left right ccenter justify'),
				'decoration'	=> array('value\\Unit', '0'),
				'indent'		=> array('Value', 'repeat repeat-x repeat-y no-repeat'),
				'transform'		=> array('Value', 'none capitalize uppercase lowercase'),
			)
		),
		// table
		'border_collapse' => '',
		'border_spacing' => '',
		'caption_side' => '',
		'empty_cells' => '',
		'table_layout' => 'auto fixed',
	);
	
	static function forge($scope, $name, $args = array())
	{
		$args = (array) $args;
		
		$class_name = isset($args[0]) ? $args[0] : 'value\\Custom';
		$args = isset($args[1]) ? $args[1] : '';
		
		$class = static::NS . $class_name;
		//debug("new $class($name, $rule)");
		
		return new $class($scope, $name, $args);
	}
	
	static function render_array($arr, $name = '', $separator)
	{
		$output = '';
		
		foreach($arr as $prop_name => $value)
		{
			$full_name = (strlen($name) ? "$name-$prop_name" : $prop_name);
			
			if(is_array($value))
			{
				$output .= static::render_array($value, $full_name, $separator);
			}
			else {
				$output .= $separator->lineStart . "{$full_name}: {$value};" . $separator->newLine;
			}
			
		}
		
		return $output;
	}
	
}

