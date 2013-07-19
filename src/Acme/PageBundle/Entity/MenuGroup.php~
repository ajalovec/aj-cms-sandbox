<?php
namespace Acme\PageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Acme\PageBundle\Entity\Page;
/**
 * @ORM\Entity
 * @ORM\Table(name="menu_group")
 */
class MenuGroup {
	
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
    /**
	 * Tytul
	 *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;	
	
    /**
	 * Tytul
	 *
     * @ORM\Column(type="string", length=100)
     */
    protected $type;	
	
	/** @ORM\ManyToMany(targetEntity="Page", mappedBy="menuGroups") */
	protected $pages;	
	
	public function __toString()
	{
		return $this->name;		
	}
	
	
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return MenuGroup
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return MenuGroup
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add pages
     *
     * @param \Acme\PageBundle\Entity\Page $pages
     * @return MenuGroup
     */
    public function addPage(\Acme\PageBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    
        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Acme\PageBundle\Entity\Page $pages
     */
    public function removePage(\Acme\PageBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }
}