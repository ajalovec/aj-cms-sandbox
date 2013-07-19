<?php
namespace Acme\TinyMceBundle\Twig\Extension;

use Stfalcon\Bundle\TinymceBundle\Twig\Extension\StfalconTinymceExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Acme\MainLogicBundle\Component\MainLogicFunctions as Functions;

/**
 * Twig Extension for TinyMce support.
 *
 * @author naydav <web@naydav.com>
 */
class TinyMceBundleExtension extends StfalconTinymceExtension
{
    public function getFunctions()
    {
        return array(
            'tinymce_init_extended' => new \Twig_Function_Method($this, 'tinymce_init_extended', array('is_safe' => array('html')))
        );
    }
	
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
	
	public function tinymce_init_extended(){
        $config  = $this->getParameter('stfalcon_tinymce.config');
		$configExtended  = $this->getParameter('acme_tiny_mce.config');
		//$config = array_merge($configExtended, $config);

		$config = Functions::arrayMultiMerge($config,$configExtended);
		
		if(isset($config['theme']['simple']['detect_document_base_url'])){
			unset($config['theme']['simple']['detect_document_base_url']);
			$config['theme']['simple']['document_base_url'] = 'http://'.$_SERVER['SERVER_NAME'];
			if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			    $config['theme']['simple']['document_base_url'] = 'https://'.$_SERVER['SERVER_NAME'];
			}
		}
 
        $baseURL = (!isset($config['base_url']) ? null : $config['base_url']);

        /** @var $assets \Symfony\Component\Templating\Helper\CoreAssetsHelper */
        $assets = $this->getService('templating.helper.assets');

        // Get path to tinymce script for the jQuery version of the editor
        $config['jquery_script_url'] = $assets->getUrl($baseURL . 'bundles/stfalcontinymce/vendor/tiny_mce/tiny_mce.jquery.js');

        // Get local button's image
        foreach ($config['tinymce_buttons'] as &$customButton) {
            $customButton['image'] = $this->getAssetsUrl($customButton['image']);
        }

        // Update URL to external plugins
        foreach ($config['external_plugins'] as &$extPlugin) {
            $extPlugin['url'] = $this->getAssetsUrl($extPlugin['url']);
        }

        // If the language is not set in the config...
        if (!isset($config['language']) || empty($config['language'])) {
            // get it from the request
            $config['language'] = $this->getService('request')->getLocale();
        }

        // Check the language code and trim it to 2 symbols (en_US to en, ru_RU to ru, ...)
        if (strlen($config['language']) > 2) {
            $config['language'] = substr($config['language'], 0, 2);
        }

        // TinyMCE does not allow to set different languages to each instance
        foreach ($config['theme'] as $themeName => $themeOptions) {
            $config['theme'][$themeName]['language'] = $config['language'];
        }

        return $this->getService('templating')->render('StfalconTinymceBundle:Script:init.html.twig', array(
            'tinymce_config' => json_encode($config),
            'include_jquery' => $config['include_jquery'],
            'tinymce_jquery' => $config['tinymce_jquery'],
            'base_url'       => $baseURL
        ));
	}
	
    public function getName()
    {
        return 'tinymce_extension';
    }
}