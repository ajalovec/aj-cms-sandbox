<?php

namespace Application\Sonata\PageBundle\Admin;

use Sonata\PageBundle\Admin\PageAdmin as BasePageAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Application\Sonata\PageBundle\Entity\Page;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PageAdmin extends BasePageAdmin implements AdminInterface
{
    protected $container;
    protected $datagridValues = array(
        '_page'       => 1,
        '_per_page'   => 100,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by'    => 'type' // field name
    );

    public function configureFormFields(FormMapper $formMapper)
    {
        
        $formMapper
          ->with($this->trans('form_page.group_main_label'))
                ->add('contentType', null, array('label'=> 'is Content','required' => false))
            ->end()
        ;
        
    	parent::configureFormFields($formMapper);
    }
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('parent', null, array('editable' => true))
            ->add('hybrid', 'text', array('template' => 'SonataPageBundle:PageAdmin:field_hybrid.html.twig'))
            ->addIdentifier('full_name')
            ->add('url')
            ->add('type')
            ->add('site')
            ->add('decorate', null, array('editable' => true))
            ->add('enabled', null, array('editable' => true))
            //->add('edited', null, array('editable' => true))
        ;
    	//parent::configureListFields($listMapper);
    }



    public function buildMenuTree($blockAdmin, MenuItemInterface $menu, Page $page)
    {
        if(!$page) return;

        if($this->getSubject() != $page) {

            $menu->addChild(
                $page->getName(),
                array(
                    'uri' => $this->generateUrl('edit', array('id' => $page->getId()))
                )
            );
        }
        $menu->addChild('Blocks tree')->setAttribute('class', 'nav-header');

//        $menu->addChild('tpl_divider')->setAttribute('class', 'divider');
        
        $buildTreeMenu = function($children, $level = -1) use ($blockAdmin, &$buildTreeMenu, &$menu) {
            ++$level;
            $prefix = str_repeat('&nbsp;&nbsp;', $level);
            if(0 < $level)
                $prefix .= "- ";
            //$prefix = "";

            foreach($children as $block)
            {
                $menu->addChild(
                    ( $prefix . ($block->getName() ?: $block->getType()) ),
                    //( $level . '. ' . ($block->getName() ?: $block->getType()) ),
                    array(
                        'uri' => $blockAdmin->generateUrl('edit', array('id' => $block->getId()))
                    )
                )
                ->setAttribute('class', "level_{$level}");

                if($blockChildren = $block->getChildren())
                {
                    $buildTreeMenu($blockChildren, $level);
                }

            }
        };

        if($children = $page->getBlocksByType('sonata.page.block.container')) {
            $buildTreeMenu($children);
        }
        
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getBlockAdmin()
    {
        return $this->container->get("sonata.page.admin.block");
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }


        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('sidemenu.link_edit_page'),
            array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );


        $menu->addChild(
            $this->trans('sidemenu.link_list_snapshots'),
            array('uri' => $admin->generateUrl('sonata.page.admin.snapshot.list', array('id' => $id)))
        );


        if (!$this->getSubject()->isHybrid()) {
        try {
            $menu->addChild(
                $this->trans('view_page'),
                array('uri' => $this->getRouteGenerator()->generate('page_slug', array('path' => $this->getSubject()->getUrl())))
            );
        } catch (RouteNotFoundException $e) {
            // avoid crashing the admin if the route is not setup correctly
//                throw $e;
        }
        }
        $menu->addChild(
            $this->trans('sidemenu.link_list_blocks'),
            array('uri' => $admin->generateUrl('sonata.page.admin.block.list', array('id' => $id)))
        );

        $this->buildMenuTree($this->getBlockAdmin(), $menu, $this->getSubject());
//        var_dump($this->getBlockAdmin());

    }
}
