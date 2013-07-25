<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AJ\Bundle\TemplateBundle\Templating;

use Symfony\Component\Templating\TemplateReference as BaseTemplateReference;
//use Symfony\Bundle\FrameworkBundle\Templating as BaseTemplateReference;

/**
 * Internal representation of a template.
 *
 * @author Victor Berchet <victor@suumit.com>
 */
class TemplateReference extends BaseTemplateReference
{
    public function __construct($templateName = null, $type = null, $controller = null, $name = null, $format = null, $engine = null)
    {
        
        $this->parameters = array(
            'type'       => $type,
            'bundle'     => $templateName,
            'controller' => $controller,
            'name'       => $name,
            'format'     => $format,
            'engine'     => $engine,
        );
    }

    /**
     * Returns the path to the template
     *  - as a path when the template is not part of a bundle
     *  - as a resource when the template is part of a bundle
     *
     * @return string A path to the template or a resource
     */
    public function getPath()
    {
        $controller = str_replace('\\', '/', $this->get('controller'));
        $path = (empty($controller) ? '' : $controller.'/').$this->get('name').'.'.$this->get('format').'.'.$this->get('engine');
        
        
        switch ($this->get('type'))
        {
            case '@template':
                $basePath = 'Resources/view';
                break;
            case '@theme':
                $basePath = 'Resources/theme/default';
                
                break;
            default:
                $basePath = 'Resources/view';
        }

        return empty($this->parameters['bundle']) ? 'views/'.$path : '@'.$this->get('bundle').'/' . trim($basePath, '/') . '/'.$path;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogicalName()
    {
        return sprintf('%s:%s:%s.%s.%s', $this->parameters['bundle'], $this->parameters['controller'], $this->parameters['name'], $this->parameters['format'], $this->parameters['engine']);
    }
}
