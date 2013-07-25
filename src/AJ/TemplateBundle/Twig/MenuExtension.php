<?php

namespace AJ\Bundle\TemplateBundle\Twig;

use Knp\Menu\Twig\Helper;

class MenuExtension extends \Twig_Extension
{
    private $helper;

    /**
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function getFunctions()
    {
        return array(
            'aj_menu_get' => new \Twig_Function_Method($this, 'get'),
            'aj_menu_render' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html'))),
        );
    }

    /**
     * Retrieves an item following a path in the tree.
     *
     * @param ItemInterface|string $menu
     * @param array                $path
     * @param array                $options
     *
     * @return ItemInterface
     */
    public function get($menu, array $path = array(), array $options = array())
    {
        return $this->helper->get($menu, $path, $options);
    }

    /**
     * Renders a menu with the specified renderer.
     *
     * @param ItemInterface|string|array $menu
     * @param array                      $options
     * @param string                     $renderer
     *
     * @return string
     */
    public function render($menu, $class = "", $depth = null)
    {
        $menu = $this->get($menu);

        if(!$menu) {
            return "";
        }
        //var_dump($menu);
        $menu->setChildrenAttribute('class',"nav {$class} ");

        $options = array(
            'currentClass' => 'active',
            'ancestorClass' => 'active',
        );
        
        if(is_int($depth)) {
            $options['depth'] = $depth;
        }

        return $this->helper->render($menu, $options, null);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'aj_template.twig.menu';
    }
}
