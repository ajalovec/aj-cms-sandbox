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
            "Page header",
            array('label'=>$this->getSubject()->getFullName())
        )->setAttribute('class', 'nav-header');
        //** Edit page * * * * * * * * * * *
        $menu->addChild(
            $this->trans('sidemenu.link_edit_page'),
            array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );
        
        //** View page * * * * * * * * * * *
        if (!$this->getSubject()->isHybrid()) {
            try {
                $menu->addChild(
                    $this->trans('view_page'),
                    array('uri' => $this->getRouteGenerator()->generate('page_slug', array('path' => $this->getSubject()->getUrl())))
                );
            } catch (RouteNotFoundException $e) {
                // avoid crashing the admin if the route is not setup correctly
                throw $e;
            }
        }

        //** Publications * * * * * * * * * * *
        $menu->addChild(
            $this->trans('sidemenu.link_list_snapshots'),
            array('uri' => $admin->generateUrl('sonata.page.admin.snapshot.list', array('id' => $id)))
        );

        //** Block list * * * * * * * * * * *
        $menu->addChild('block_list_divider')->setAttribute('class', 'divider');
        $menu->addChild(
            $this->trans('sidemenu.link_list_blocks'),
            array('uri' => $admin->generateUrl('sonata.page.admin.block.list', array('id' => $id)))
        )->setAttribute('class', 'nav-header');

        $this->buildBlocksTree($this->getBlockAdmin(), $menu, $this->getSubject());

        //** Page edit list * * * * * * * * * * *
        $menu->addChild('page_list_divider')->setAttribute('class', 'divider');
        $queryParams = array(
            'site' => $this->getSubject()->getSite(),
            'parent' => null,
            //'contentType'=>true,
        );
        $menu->addChild(
            "Page list",
            array('uri' => $admin->generateUrl('list', ['filter'=> ['parent'=> ['value'=> $this->getSubject()->getParent()]]]))
        )->setAttribute('class', 'nav-header');

        $this->buildPagesTree($menu, $this->pageManager->findBy($queryParams));
        //$this->buildPagesTree($menu, $this->pageManager->loadPages($this->getSubject()->getSite()));
    }

    public function buildPagesTree(MenuItemInterface $menu, $children)
    {
        $pageAdmin = $this;

        $buildTreeMenu = function($children, $level = -1) use ($pageAdmin, &$buildTreeMenu, &$menu) {
            ++$level;
            $prefix = str_repeat('&nbsp;&nbsp;', $level);
            if(0 < $level)
                $prefix .= "- ";
            //$prefix = "";

            foreach($children as $page)
            {
                $menu->addChild(
                    ( $prefix . ($page->getName()) ),
                    //( $level . '. ' . ($page->getName() ?: $page->getType()) ),
                    array(
                        'uri' => $pageAdmin->generateUrl('edit', array('id' => $page->getId()))
                    )
                )
                ->setAttribute('class', "level_{$level}");

                if($pageChildren = $page->getChildren())
                {
                    $buildTreeMenu($pageChildren, $level);
                }

            }
        };

        
        //$children = $this->pageManager->findBy($pagesQuery);
        if($children) {
            $buildTreeMenu($children);
        }
    }


    public function buildBlocksTree($admin, MenuItemInterface $menu, Page $page)
    {
        if(!$page) return;
        $admin = $this;
//        $menu->addChild('tpl_divider')->setAttribute('class', 'divider');
        $id = $admin->getRequest()->get('id');
        $id = $page->getId();
        $buildTreeMenu = function($children, $level = -1) use ($admin, &$buildTreeMenu, &$menu, $id) {
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
                        //'uri' => $admin->generateUrl('edit', array('id' => $block->getId()))
                        'uri' => $admin->generateUrl('sonata.page.admin.block.edit', array('id' => $id, 'childId'=>$block->getId()))
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
}
