<?php
namespace Acme\AssortmentBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Acme\TreeBundle\Model\AbstractTree as BaseTree;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity
 * @ORM\Table(name="acme_assortment_category")
 * @ORM\Entity(repositoryClass="Acme\AssortmentBundle\Entity\AssortmentCategoryRepository")
 */
class AssortmentCategory extends BaseTree
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
	 * @ORM\OneToOne(targetEntity="Acme\PageBundle\Entity\Route",cascade={"persist", "remove"})
	 */
	protected $route;
	
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
     * @ORM\ManyToOne(targetEntity="AssortmentCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="AssortmentCategory", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected $children;	
	
    /**
     * @ORM\OneToMany(targetEntity="AssortmentPage", mappedBy="assortmentCategory")
     */
    protected $assortmentPages;
	
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
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
		$this->assortmentPages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AssortmentCategory
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
     * @return AssortmentCategory
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
     * @return AssortmentCategory
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
     * Set lft
     *
     * @param integer $lft
     * @return AssortmentCategory
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
     * @return AssortmentCategory
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
     * @return AssortmentCategory
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
     * @return AssortmentCategory
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
     * @return AssortmentCategory
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
     * Set parent
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentCategory $parent
     * @return AssortmentCategory
     */
    public function setParent(\Acme\AssortmentBundle\Entity\AssortmentCategory $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Acme\AssortmentBundle\Entity\AssortmentCategory 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentCategory $children
     * @return AssortmentCategory
     */
    public function addChildren(\Acme\AssortmentBundle\Entity\AssortmentCategory $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentCategory $children
     */
    public function removeChildren(\Acme\AssortmentBundle\Entity\AssortmentCategory $children)
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
     * Add assortmentPages
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentPage $assortmentPages
     * @return AssortmentCategory
     */
    public function addAssortmentPage(\Acme\AssortmentBundle\Entity\AssortmentPage $assortmentPages)
    {
        $this->assortmentPages[] = $assortmentPages;
    
        return $this;
    }

    /**
     * Remove assortmentPages
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentPage $assortmentPages
     */
    public function removeAssortmentPage(\Acme\AssortmentBundle\Entity\AssortmentPage $assortmentPages)
    {
        $this->assortmentPages->removeElement($assortmentPages);
    }

    /**
     * Get assortmentPages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssortmentPages()
    {
        return $this->assortmentPages;
    }
}