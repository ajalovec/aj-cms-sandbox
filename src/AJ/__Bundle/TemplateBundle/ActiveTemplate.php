<?php
namespace AJ\Bundle\TemplateBundle;


use Liip\ThemeBundle\ActiveTheme;

class ActiveTemplate implements ActiveTemplateInterface
{
	//public function getThemes()
	//public function setThemes(array $themes)
	//public function getName()
	//public function setName($name)
	private $theme;
	private $baseUrl;
	private $name;
	private $templateBundle;

	public function __construct(ActiveTheme $activeTheme, $baseUrl = '/')
	{
		$this->theme = $activeTheme;

		$protocolPos = strpos($baseUrl, '://');
		$this->baseUrl 	= $protocolPos > 0
						? substr(rtrim($baseUrl, '/'), ($protocolPos+1)) . '/'
						: '/' . trim($baseUrl, '/') . '/';
	}

	public function getName()
	{
		return $this->name;
	}

	public function getBundleName()
	{
		if(is_object($this->templateBundle))
		{
			return $this->templateBundle->getName();
		}

		return '';
	}

	public function getBundle()
	{
		return $this->templateBundle;
	}

	public function setBundle($templateBundle)
	{
		if(isset($this->templateBundle))
		{
			return;
		}
		
		$this->templateBundle = $templateBundle;

		$siteName = str_replace(array('Site\\', '\\SiteBundle'), '', $this->templateBundle->getNamespace());
		$this->name = strtolower(str_replace('\\', '_', $siteName));
	}




	public function getUrl()
	{
		return $this->baseUrl;
	}

	public function getAssetUrl($relative = false)
	{
		return ($relative ? '' : $this->baseUrl) . 'assets/';
	}

	public function getThemeAssetUrl($relative = false)
	{
		return $this->getAssetUrl($relative) . 'themes/' . $this->getName() . '_' . $this->theme->getName() . '/';
	}
}
