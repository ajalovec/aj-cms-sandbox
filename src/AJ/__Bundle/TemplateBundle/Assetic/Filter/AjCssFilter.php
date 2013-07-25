<?php
 
namespace AJ\Bundle\TemplateBundle\Assetic\Filter;
 
use Assetic\Asset\AssetInterface;
use Assetic\Filter\BaseCssFilter;
use Symfony\Component\HttpFoundation\Request;


function dumpcss($s)
{
	return "/*\n" . print_r($s, true) . "\n*/";
}

class AjCssFilter extends BaseCssFilter
{
 	private $basePath = '/';
    private $loadedAssets = array();

 	public function __construct($path = "/")
 	{
 		$protocolPos = strpos($path, '://');

 		if($protocolPos > 0)
 		{
 			$this->basePath = substr(rtrim($path, '/'), ($protocolPos+1)) . '/';
 		}
 		else {
	 		$this->basePath = '/' . trim($path, '/') . '/';
	 	}
 	}

 	private function getAssetPaths(AssetInterface $asset)
	{
		$assetId = sha1($asset->getSourcePath());

		if(isset($this->loadedAssets[$assetId]))
		{
			return $this->loadedAssets[$assetId];
		}

		$themeUrl = str_replace('_controller/', $this->basePath, $asset->getTargetPath());

		return $this->loadedAssets[$assetId] = array(
			'web' => $this->basePath,
			'theme' => static::path_parent($themeUrl, '/', 2),
		);
	}

	public function filterLoad(AssetInterface $asset)
	{
		if(substr($asset->getSourcePath(), -5) !== '.less')
		{
			return;
		}
		$paths = static::getAssetPaths($asset);

		$content = static::printLessVariables($paths);
		$content .= $asset->getContent();

		$asset->setContent($content);
	}

	public function filterDump(AssetInterface $asset)
	{
		if(substr($asset->getSourcePath(), -5) !== '.less')
		{
			return;
		}

		$paths = static::getAssetPaths($asset);
		
		$content = static::replaceCssUrls($paths, $asset->getContent());
		

		$asset->setContent($content);
	}

	
	static private function replaceCssUrls(array $paths, $content)
	{
		$stack = array();

		foreach ($paths as $name => $path)
		{
	 		$stack["url({$name}/"] 	= "url({$path}/";
	 		$stack["url(\"{$name}/"] = "url(\"{$path}/";
	 		$stack["url(\'{$name}/"] = "url(\'{$path}/";
		}

		return str_replace(array_keys($stack), $stack, $content);
 	}
 

/* ## # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	# Helper static functions
	# # # # # # # # # # # # #*/
	static private function printLessVariables($paths)
	{
		$string = "";

		foreach ($paths as $name => $path)
		{	
 			$string .= "@{$name}Url: '{$path}';";
		}

		return $string;
 	}

	static private function path_parent($string, $separator, $length = 1)
	{
		$arr = explode($separator, rtrim($string, $separator));
		for($i = 0; $i < $length; $i++)
		{
			array_pop($arr);
		}
		return implode('/', $arr);
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

