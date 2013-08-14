<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Sonata\PageBundle\Admin;

use Sonata\PageBundle\Admin\BlockAdmin as BaseBlockAdmin;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Sonata\CacheBundle\Cache\CacheManagerInterface;

use Sonata\BlockBundle\Block\BlockServiceManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;

/**
 * Admin class for the Block model
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class BlockAdmin extends BaseBlockAdmin
{
    protected $container;

    protected $datagridValues = array(
        '_page'       => 1,
        '_per_page'   => 100,
        '_sort_order' => 'ASC', // sort direction
        '_sort_by'    => 'page' // field name
    );

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('page', null, array())
            ->addIdentifier('full_name', null, array('editable' => true))
            ->add('type')
            ->add('enabled')
            ->add('updatedAt')
            ->add('position')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $block = $this->getSubject();
        if(null == $block->getType())
        {
            //$block->setType("sonata.page.block.container");
        }
        /*
sonata_type_model_list
sonata_type_model_reference
sonata_type_immutable_array
*/
        // add name on all forms
        $formMapper
        ->with($this->trans('form_page.group_main_label'))
            ->add('name')
        ->end()
        ->with($this->trans('form_page.group_advanced_label'))
            ->add('enabled')
            ->add('type', null, array('disabled' => true))
            ->add('page')
            ->add('parent')
        ->end()
        ;

        $isContainerRoot = $block && $block->getType() == 'sonata.page.block.container' && !$this->hasParentFieldDescription();
        $isStandardBlock = $block && $block->getType() != 'sonata.page.block.container' && !$this->hasParentFieldDescription();

        if (($isContainerRoot || $isStandardBlock)) {
            
            $service = $this->blockManager->get($block);

            if ($block->getId() > 0) {
                $service->buildEditForm($formMapper->with($this->trans('form_page.group_main_label')), $block);
            } else {
                $service->buildCreateForm($formMapper->with($this->trans('form_page.group_main_label')), $block);
            }

            $formMapper->end();

        } else {
            $formMapper
                ->add('type', 'sonata_block_service_choice', array(
                    'context' => 'cms'
                ))
                ->add('position');
        }
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getPageAdmin()
    {
        return $this->container->get("sonata.page.admin.page");
    }
    /*
    private $blocksTreeMenu;

    public function buildTreeMenu(MenuItemInterface $menu, $$blocks)
    {
        //if($children->isEmpty()) return;

        $menu->addChild('Blocks tree')->setAttribute('class', 'nav-header');

//        $menu->addChild('tpl_divider')->setAttribute('class', 'divider');
        
        $buildTreeMenu = function($$blocks, $level = -1) use (&$this, &$buildTreeMenu, &$menu) {
            ++$level;
            $prefix = str_repeat('&nbsp;&nbsp;', $level);
            if(0 < $level)
                $prefix .= "- ";
            //$prefix = "";

            foreach($$blocks as $block)
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

        $buildTreeMenu($blocks);
        
    }
*/
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $page = $this->getSubject()->getPage();
        
        // Parent edit
        if($parent = $this->getSubject()->getParent())
        {
            $menu->addChild(
                "Parent block",
                array('uri' => $this->generateUrl('edit', array('id' => $parent->getId())))
            );
        }


        $children = $this->getSubject()->getChildren();

        if(!$children->isEmpty())
        {
            $menu->addChild('Children blocks')->setAttribute('class', 'nav-header');

            foreach($children as $child)
            {
                $menu->addChild(
                    ($child->getName() ?: $child->getType()),
                    array('uri' => $this->generateUrl('edit', array('id' => $child->getId())))
                );
            }
        }

        
        $this->getPageAdmin()->buildMenuTree($this, $menu, $this->getSubject()->getPage());
        //$pageMenu->setDisplay(false);
    }
}
