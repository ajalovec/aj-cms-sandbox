<?php
# Test/MyBundle/Twig/Extension/MyBundleExtension.php

namespace Acme\PageBundle\Twig\Extension;
use Symfony\Component\HttpKernel\KernelInterface;

class AcmePageBundleExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'getFirstElement' => new \Twig_Function_Method($this, 'getFirstElement')
        );
    }
   
    /**
     * Converts a string to time
     *
     * @param string $string
     * @return int
     */
    public function getFirstElement ($array = array())
    {
    	foreach ($array as $key => $value) {
			return $value;
		}
        return null;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'acme_page';
    }
}