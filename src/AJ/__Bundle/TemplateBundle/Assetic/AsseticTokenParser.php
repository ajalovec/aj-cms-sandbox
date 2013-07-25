<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AJ\Bundle\TemplateBundle\Assetic;

use Assetic\Asset\AssetInterface;
use Symfony\Bundle\AsseticBundle\Twig\AsseticTokenParser as BaseAsseticTokenParser;

use Symfony\Bundle\AsseticBundle\Exception\InvalidBundleException;
use Symfony\Component\Templating\TemplateNameParserInterface;


use Assetic\Factory\AssetFactory;
use AJ\Bundle\TemplateBundle\ActiveTemplate;

class AsseticTokenParser extends BaseAsseticTokenParser
{
    private $factory;
    private $tag;
    private $output;
    private $single;
    private $extensions;
    private $activeTemplate;

    public function __construct(AssetFactory $factory, $tag, $output, $single = false, array $extensions = array())
    {
        parent::__construct($factory, $tag, $output, $single, $extensions);
        $this->factory    = $factory;
        $this->tag        = $tag;
        $this->output     = $output;
        $this->single     = $single;
        $this->extensions = $extensions;
    }
    
    public function setActiveTemplate(/*ActiveTemplate*/ $activeTemplate)
    {
        $this->activeTemplate = $activeTemplate;
    }

    public function parse(\Twig_Token $token)
    {
        $node = $this->parseNode($token);
        extract($node);

        switch ($this->tag) {
            case 'tpl_stylesheets':
                array_push($node['filters'], 'ajcss', '?yui_css');
                break;
            case 'tpl_javascripts':
                array_push($node['filters'], '?yui_js');
                break;
        }
        array_unshift($node['filters'], 'ajimport');
        //debug($node);

        // create asset instance
        $asset = $this->factory->createAsset($node['inputs'], $node['filters'], $node['attributes'] + array('name' => $node['name']));

        // create html include tag
        return $this->createNode($asset, $node['body'], $node['inputs'], $node['filters'], $node['name'], $node['attributes'], $token->getLine(), $this->getTag());
    }


    protected function createNode(AssetInterface $asset, \Twig_NodeInterface $body, array $inputs, array $filters, $name, array $attributes = array(), $lineno = 0, $tag = null)
    {
        $n = parent::createNode($asset, $body, $inputs, $filters, $name, $attributes, $lineno, $tag);
        //debug($n);
        return $n;
    }

    private function parseNode(\Twig_Token $token)
    {
        $inputs = array();
        $filters = array();
        $name = null;
        $attributes = array(
            'output'   => $this->output,
            'var_name' => 'asset_url',
            'vars'     => array(),
        );

        $stream = $this->parser->getStream();
        
        while (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {
            if ($stream->test(\Twig_Token::STRING_TYPE)) {
                // '@jquery', 'js/src/core/*', 'js/src/extra.js'
                $inputs[] = $stream->next()->getValue();
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'filter')) {
                // filter='yui_js'
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $filters = array_merge($filters, array_filter(array_map('trim', explode(',', $stream->expect(\Twig_Token::STRING_TYPE)->getValue()))));
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'output')) {
                // output='js/packed/*.js' OR output='js/core.js'
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes['output'] = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'name')) {
                // name='core_js'
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $name = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'as')) {
                // as='the_url'
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes['var_name'] = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'debug')) {
                // debug=true
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes['debug'] = 'true' == $stream->expect(\Twig_Token::NAME_TYPE, array('true', 'false'))->getValue();
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'combine')) {
                // combine=true
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes['combine'] = 'true' == $stream->expect(\Twig_Token::NAME_TYPE, array('true', 'false'))->getValue();
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, 'vars')) {
                // vars=['locale','browser']
                $stream->next();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $stream->expect(\Twig_Token::PUNCTUATION_TYPE, '[');

                while ($stream->test(\Twig_Token::STRING_TYPE)) {
                    $attributes['vars'][] = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();

                    if (!$stream->test(\Twig_Token::PUNCTUATION_TYPE, ',')) {
                        break;
                    }

                    $stream->next();
                }

                $stream->expect(\Twig_Token::PUNCTUATION_TYPE, ']');
            } elseif ($stream->test(\Twig_Token::NAME_TYPE, $this->extensions)) {
                // an arbitrary configured attribute
                $key = $stream->next()->getValue();
                $stream->expect(\Twig_Token::OPERATOR_TYPE, '=');
                $attributes[$key] = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();
            } else {
                $token = $stream->getCurrent();
                throw new \Twig_Error_Syntax(sprintf('Unexpected token "%s" of value "%s"', \Twig_Token::typeToEnglish($token->getType(), $token->getLine()), $token->getValue()), $token->getLine());
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse(array($this, 'testEndTag'), true);

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        if ($this->single && 1 < count($inputs)) {
            $inputs = array_slice($inputs, -1);
        }
        
        if (!$name) {
            $name = $this->factory->generateAssetName($inputs, $filters, $attributes);
        }

        return compact('body','name','inputs','filters','attributes');
    }

}
