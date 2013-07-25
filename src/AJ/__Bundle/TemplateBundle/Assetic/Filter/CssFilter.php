<?php
 
namespace AJ\Bundle\TemplateBundle\Assetic\Filter;
 
use Assetic\Asset\AssetInterface;
use Assetic\Filter\BaseCssFilter;
use Symfony\Component\HttpFoundation\Request;

class CssFilter extends BaseCssFilter
{
 	private $basePath = '/';
    private $loadedAssets = array();

 	public function __construct($path)
 	{
 		$this->basePath = rtrim($path, '/') . '/';
 	}

 	

	public function filterLoad(AssetInterface $asset)
	{
		if(substr($asset->getSourcePath(), -5) !== '.less')
		{
			return;
		}

		$content = static::printLessVariables(static::getAssetPaths($asset));
		$content .= $asset->getContent();

		$asset->setContent($content);
	}

	public function filterDump(AssetInterface $asset)
	{
		if(substr($asset->getSourcePath(), -5) !== '.less')
		{
			return;
		}
		
		$content = static::replaceCssUrls(static::getAssetPaths($asset), $asset->getContent());
		

		$asset->setContent($content);
	}

	private function getAssetPaths(AssetInterface $asset)
	{
		$assetId = sha1($asset->getSourcePath());

		if(isset($this->loadedAssets[$assetId]))
		{
			return $this->loadedAssets[$assetId];
		}

		$themePath = static::getAssetPath($asset->getTargetPath(), 1);

		// test for prod environment
		if(false)
		{
			$themePath = 'assets/themes/t2_oprema_default';
			$this->basePath = '/';
		}

		$themeUrl = $this->basePath . $themePath;
		$tplUrl = substr($themeUrl, 0, strrpos($themeUrl, '/themes/'));
		return $this->loadedAssets[$assetId] = array(
			//'basePath' => $this->basePath,
			//'themePath' => $themePath,
			//'targetPath' => $asset->getTargetPath(),
			'web' => rtrim($this->basePath, '/'),
			'tpl' => $tplUrl,
			'theme' => $themeUrl,
		);
	}

	static private function getAssetPath($path, $length = 1)
	{
		$separator = '/';
		$parts = explode($separator, trim(dirname($path), $separator));
		if(count($parts) > 0 && $parts[0] == '_controller')
		{
			array_shift($parts);
		}
		for($i = 0; $i < $length; $i++)
		{
			array_pop($parts);
		}
		return implode('/', $parts);
	}
	
	static private function replaceCssUrls(array $paths, $content)
	{
		$stack = array();

		foreach ($paths as $name => $path)
		{
	 		$stack["url({$name}"] 	= "url({$path}/";
	 		$stack["url(\"{$name}/"] = "url(\"{$path}/";
	 		$stack["url('{$name}/"] = "url('{$path}/";
		}

		return str_replace(array_keys($stack), $stack, $content);
 	}
 

/* ## # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	# Helper static functions
	# # # # # # # # # # # # #*/
	static private function printLessVariables($paths)
	{
		$string = "";
		$vars = "vars {";
		$urls = "urls {";
		foreach ($paths as $name => $path)
		{	
 			$string .= "@{$name}Url: '{$path}';";
 			$vars .= "\n\t{$name}Url: @{$name}Url;";
 			$urls .= "content: '{$path}';";
		}
		$vars .= "}";
		$urls .= "}";

		return $string.$urls;
 	}


	static private function printRelativePath($length)
	{
		$path = '';
		for($i=1; $i < $length; $i++)
		{
			$path .= '/..';
		}

		return $path;
		$path .= '../';
    }

	static private function pathinfo($request)
	{
	 $debug
        = "\n getScheme:\t\t" . $request->getScheme()
        . "\n getHost:\t\t" . $request->getHost()
        . "\n getHttpHost:\t\t" . $request->getHttpHost()
        . "\n getSchemeAndHttpHost:\t" . $request->getSchemeAndHttpHost()
        . "\n getPort:\t\t" . $request->getPort()
        . "\n getPathInfo:\t\t" . $request->getPathInfo()
        . "\n getUserInfo:\t\t" . $request->getUserInfo()
        . "\n"
        . "\n getScriptName:\t\t" . $request->getScriptName()
        . "\n getBaseUrl:\t\t" . $request->getBaseUrl()
        . "\n getBasePath:\t\t" . $request->getBasePath()
        . "\n"
        . "\n getRequestUri:\t\t" . $request->getRequestUri()
        . "\n"
        . "\n getUriForPath:\t\t" . $request->getUriForPath('/custom/url/name')
        . "\n getUri:\t\t" . $request->getUri()
        . "\n getQueryString:\t" . $request->getQueryString()
        . "\n getContentType:\t\t" . $request->getContentType()
        . "\n getLocale:\t\t" . $request->getLocale()
        . "\n getRequestFormat:\t" . $request->getRequestFormat()
        . "\n getMimeType:\t\t" . $request->getMimeType('html')
        . "\n getFormat:\t\t" . $request->getFormat('text/html')
        . "\n getLanguages:\t\t" . print_r($request->getLanguages('text/html'), true)
        . "\n getCharsets:\t\t" . print_r($request->getCharsets('text/html'), true)
        . "\n" . print_r($request->server, true)
        . "\n";

        return $debug;
    }

}

