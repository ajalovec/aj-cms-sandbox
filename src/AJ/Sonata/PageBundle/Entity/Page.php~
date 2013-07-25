<?php
namespace Acme\PageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Acme\TreeBundle\Model\AbstractTree as BaseTree;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\PageBundle\Entity\MenuGroup;
/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Acme\TreeBundle\Entity\TreeRepository")
 */
class Page extends BaseTree
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
	 * Tytul
	 *
     * @ORM\Column(type="string", length=1000)
     */
    protected $title;	
	
    /**
	 * Menu name
	 *
     * @ORM\Column(name="menu_name", type="string", length=1000, nullable=true)
     */
    protected $menuName;		
	
    /**
	 * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, nullable=true)
     */
    protected $slug;

	/**
	 * Ścieżka
	 *
	 * @ORM\OneToOne(targetEntity="Route",cascade={"persist", "remove"})
	 */
	protected $route;
	
	/**
	 * Treść
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
    protected $body;

	/**
	 * ################# GEDMO Tree
	 */
	
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    protected $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    protected $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;	
	
	/** @ORM\ManyToMany(targetEntity="MenuGroup", inversedBy="pages") */
	protected $menuGroups;
	
	/** @ORM\ManyToMany(targetEntity="PageModule", inversedBy="pages") */
	protected $pageModules;	
	
	/**
	 * Niezbedne metody
	 */
	
 	public function getFieldForSlug()
	{
		$menuName = $this->getMenuName();
		if(empty($menuName)){
			return $this->getTitle();
		}else{
			return $this->getMenuName();
		}
	}
	
    public function setParent(Page $parent = null)
    {
        $this->parent = $parent;    
    }

    public function getParent()
    {
        return $this->parent;   
    }	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
		$this->menuGroups = new ArrayCollection();
		$this->pageModules = new ArrayCollection();
		if($this->route !== null){
			$this->routeName = $this->route->getRouteName();
		}else{
			$this->routeName = null;
		}
		
    }
  

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set menuName
     *
     * @param string $menuName
     * @return Page
     */
    public function setMenuName($menuName)
    {
        $this->menuName = $menuName;
    
        return $this;
    }

    /**
     * Get menuName
     *
     * @return string 
     */
    public function getMenuName()
    {
        return $this->menuName;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Page
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Page
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    
        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Page
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    
        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Page
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    
        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Page
     */
    public function setRoot($root)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set route
     *
     * @param \Acme\PageBundle\Entity\Route $route
     * @return Page
     */
    public function setRoute(\Acme\PageBundle\Entity\Route $route = null)
    {
        $this->route = $route;
    
        return $this;
    }

    /**
     * Get route
     *
     * @return \Acme\PageBundle\Entity\Route 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Add children
     *
     * @param \Acme\PageBundle\Entity\Page $children
     * @return Page
     */
    public function addChildren(\Acme\PageBundle\Entity\Page $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Acme\PageBundle\Entity\Page $children
     */
    public function removeChildren(\Acme\PageBundle\Entity\Page $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add menuGroups
     *
     * @param \Acme\PageBundle\Entity\MenuGroup $menuGroups
     * @return Page
     */
    public function addMenuGroup(\Acme\PageBundle\Entity\MenuGroup $menuGroups)
    {
        $this->menuGroups[] = $menuGroups;
    
        return $this;
    }

    /**
     * Remove menuGroups
     *
     * @param \Acme\PageBundle\Entity\MenuGroup $menuGroups
     */
    public function removeMenuGroup(\Acme\PageBundle\Entity\MenuGroup $menuGroups)
    {
        $this->menuGroups->removeElement($menuGroups);
    }

    /**
     * Get menuGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenuGroups()
    {
        return $this->menuGroups;
    }

    /**
     * Add pageModules
     *
     * @param \Acme\PageBundle\Entity\PageModule $pageModules
     * @return Page
     */
    public function addPageModule(\Acme\PageBundle\Entity\PageModule $pageModules)
    {
        $this->pageModules[] = $pageModules;
    
        return $this;
    }

    /**
     * Remove pageModules
     *
     * @param \Acme\PageBundle\Entity\PageModule $pageModules
     */
    public function removePageModule(\Acme\PageBundle\Entity\PageModule $pageModules)
    {
        $this->pageModules->removeElement($pageModules);
    }

    /**
     * Get pageModules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPageModules()
    {
        return $this->pageModules;
    }
}