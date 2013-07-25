<?php

namespace AJ\Component\DependencyInjection;

/*
$arr = array(
	'sandbox' => array(
		'name'			=> 'admin_page'
	),
	'database' => array(
		'name'			=> 'partyslo_new',
		'host'			=> 'localhost',
		'port'			=> FALSE,
		'username'			=> 'root',
		'password'			=> 'joze123'
	)
);
$arr['stack']['stack'] = 'asdasd';

$p = new Params($arr);
//$p = new Params();
$p->database->test = $arr['database'];
$json = $p->database->getJSON();
debug($json);
$p2 = Params::JSON($json);
$p2->remove('name');
//$p2->addString($json);
debug($p2);
debug($p->database);



//debug($p->get('database2.obj.asd.gre'));
		
$p->set('database.test', $arr['database']);
$p->set('database.name.as', 'aaaaaaa');
//debug($p->get('database')->toArray());
//debug($p->remove('database'));

$p2 = Params::JSON($p->database->toJSON());
//$p2->remove('name');
$p2->neki = $p->database;
//$p2->addString($json);
debug($p2('array'));

 */

class Params extends ParamsBase {
	
	
	function __construct($stack = array())
	{
		parent::__construct($this, $stack);
	}
	
	static function JSON($string)
	{
		$p = new self();
		$p->addJSON($string);
		
		return $p;
	}
	
	static function String($string)
	{
		$p = new self();
		$p->addString($string);
		
		return $p;
	}
	
	

	
}


