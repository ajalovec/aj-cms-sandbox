<?php

namespace AJ;


class Functions
{
	
	static function debug($a)
	{
		echo "<pre>" . print_r($a, true) . "</pre>";
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'method'	=> false
		));
	}

	public function getName()
	{
		return 'form_base';
	}
}
