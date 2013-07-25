<?php
 
namespace AJ\Bundle\TemplateBundle\Assetic\Filter;
 
use Assetic\Asset\AssetInterface;
use Assetic\Filter\BaseCssFilter;
use Symfony\Component\HttpFoundation\Request;

class ImportFilter extends BaseCssFilter
{
 
 	public function filterLoad(AssetInterface $asset)
	{
		$content = $this->parseContent($asset->getContent(), $this->getPathsArray($asset));
		
		//dump($content);

		$asset->setContent($content);
	}
 
	

	private function getPathsArray($asset)
	{
		$request = Request::createFromGlobals();
		
		$root = realpath(
			str_replace('/', '\\', realpath(dirname($request->server->get('SCRIPT_FILENAME')))) . '/../'
		);

		$current = realpath(dirname(realpath($asset->getSourceRoot() . '/' . $asset->getSourcePath())));
		$keyword = '\\template';
		$tpl = substr($current, 0, (strrpos($current, $keyword)+strlen($keyword)));

		return compact('public','tpl','current');
    }

	private function parseContent($content, $pathsArray)
	{
		$imports = array();
        preg_match_all("/\#?@import\s?[\'\"]{1}([^\'\"\;]+)[\'\"]{1}\;/s", $content, $imports, PREG_OFFSET_CAPTURE);

        //$imports[0] = array_reverse($imports[0], true)
       
        foreach($imports[0] as $i => $import)
        {
            $file = $import[0][0] == '#' ? '' : $this->loadFile($imports[1][$i][0], $pathsArray);

            $content = str_replace($import[0], $file, $content);
            //$content = str_replace($import[0], ($file ? $file : ('.test{'.$imports[1][$i][0].'}')), $content);

        	//dump($imports[1][$i]);
        	//dump($import);
        }

       return $content;
 	}

	private function loadFile($file, $pathsArray)
	{
		if(!$file = $this->generatePath($file, $pathsArray)) return "";
		
		$content = "";

		switch(pathinfo($file, PATHINFO_EXTENSION))
		{
			case 'js':
				$content = file_get_contents($file);
				break;
			case 'css':
			default:
				$content = file_get_contents($file);
				break;
		}

		$pathsArray['current'] = dirname($file);
		$content = $this->parseContent($content, $pathsArray);
		//dump($currentPath);
		//dump($file."\n");
		//dump($content);
		return $content;
	}
 
 
	private function generatePath($path, $pathsArray)
	{
		$parts = explode(':/', $path, 2);

		if(count($parts) == 1)
		{
			array_unshift($parts, 'current');
		}
		//dump($parts);
		//dump($path);
		//dump($pathsArray);
		//dump("\n\n\n");
		$path = realpath($pathsArray[$parts[0]] . '//' . $parts[1]);
		
		return ($path && is_file($path) ? $path : null);
	}
 
 
	public function filterDump(AssetInterface $asset)
	{
		
        //$content = dumpcss(implode("\n", $paths));
        //$content = dumpcss($asset->getVars());
		
		$content = $asset->getContent();

		//$dump = dumpcss($asset);
		//Do something to $content
		//$content = str_replace("body", "blabla", $content);
		$asset->setContent($content);
	}
 
 
}

