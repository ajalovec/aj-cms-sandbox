<?php
namespace Acme\AssortmentBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\PageBundle\Entity\BasePage;
/**
 * @ORM\Entity
 * @ORM\Table(name="acme_assortment_page")
 */
class AssortmentPage extends BasePage
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
	 * Treść
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="AssortmentCategory", inversedBy="assortmentPages")
     * @ORM\JoinColumn(name="assortment_category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $assortmentCategory;
    
    /**
     * @ORM\OneToMany(targetEntity="AssortmentPageHasMedia", cascade={"persist", "remove"}, mappedBy="assortmentPage", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
	 */
	protected $assortmentPageHasMedias;
	
	
    public function setAssortmentPageHasMedias($assortmentPageHasMedias)
    {
        $this->assortmentPageHasMedias = new ArrayCollection();

        foreach ($assortmentPageHasMedias as $assortmentPageHasMedia) {
            $this->addAssortmentPageHasMedia($assortmentPageHasMedia);
        }
    }
	
	 public function addAssortmentPageHasMedia(\Acme\AssortmentBundle\Entity\AssortmentPageHasMedia $assortmentPageHasMedias)
	{
	    $assortmentPageHasMedias->setAssortmentPage($this);
	    $this->assortmentPageHasMedias[] = $assortmentPageHasMedias;
	   
	    return $this;
	}
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
	
    public function __toString()
    {
        return (string)$this->getTitle();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assortmentPageHasMedias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AssortmentPage
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
     * @return AssortmentPage
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
     * @return AssortmentPage
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
     * @return AssortmentPage
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
     * Set route
     *
     * @param \Acme\PageBundle\Entity\Route $route
     * @return AssortmentPage
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
     * Set assortmentCategory
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentCategory $assortmentCategory
     * @return AssortmentPage
     */
    public function setAssortmentCategory(\Acme\AssortmentBundle\Entity\AssortmentCategory $assortmentCategory = null)
    {
        $this->assortmentCategory = $assortmentCategory;
    
        return $this;
    }

    /**
     * Get assortmentCategory
     *
     * @return \Acme\AssortmentBundle\Entity\AssortmentCategory 
     */
    public function getAssortmentCategory()
    {
        return $this->assortmentCategory;
    }

    /**
     * Remove assortmentPageHasMedias
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentPageHasMedia $assortmentPageHasMedias
     */
    public function removeAssortmentPageHasMedia(\Acme\AssortmentBundle\Entity\AssortmentPageHasMedia $assortmentPageHasMedias)
    {
        $this->assortmentPageHasMedias->removeElement($assortmentPageHasMedias);
    }

    /**
     * Get assortmentPageHasMedias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssortmentPageHasMedias()
    {
        return $this->assortmentPageHasMedias;
    }
}