<?php
namespace AJ\Bundle\TemplateBundle\Twig;

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Default base class for compiled templates.
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
abstract class Template extends \Twig_Template
{
	const TYPE_PAGE 		= 'page';
	const TYPE_LAYOUT 		= 'layout';
	const TYPE_COMPONENT 	= 'component';
	const TYPE_FORM 		= 'form';
	const TYPE_LIST 		= 'list';
	const TYPE_ITEM 		= 'item';

	private $ajViewType 	= null;

	public function juhejDela()
	{
		echo 'to je pa moja template funkcija, ko se zmeri izvaja je tud ko je v cachu.';
	}
	public function setViewType($name)
	{
		$this->ajViewType = is_string($name) ? strtolower($name) : null;
	}

    

	public function ajGetBlocks()
	{
		$b = $this->getBlockNames();

		return implode(', ', $b);
	}
	/*
	public function render(array $context)
	{
		$level = ob_get_level();
		ob_start();
		try {
			$this->display($context);
		} catch (Exception $e) {
			while (ob_get_level() > $level) {
				ob_end_clean();
			}

			throw $e;
		}

		return ob_get_clean();
	}
	 */
}
