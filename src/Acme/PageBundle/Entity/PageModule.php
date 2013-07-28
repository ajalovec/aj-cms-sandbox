<?php
namespace Acme\PageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Acme\PageBundle\Entity\Page;
/**
 * @ORM\Entity
 * @ORM\Table(name="acme_page_module")
 */
class PageModule {
	
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
	 * Modul
	 *
     * @ORM\Column(type="string", length=100, name="block_name")
     */
    protected $blockName;	
	
    /**
	 * pomocnicze id
	 *
     * @ORM\Column(type="string", length=100, name="block_sub_id")
     */
    protected $blockSubId;
	

	/** @ORM\ManyToMany(targetEntity="Page", mappedBy="pageModules") */
	protected $pages;	
	
    /**
	 * Forward
	 *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $forward;	
	
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
     * @return PageModule
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
     * Set blockName
     *
     * @param string $blockName
     * @return PageModule
     */
    public function setBlockName($blockName)
    {
        $this->blockName = $blockName;
    
        return $this;
    }

    /**
     * Get blockName
     *
     * @return string 
     */
    public function getBlockName()
    {
        return $this->blockName;
    }

    /**
     * Set blockSubId
     *
     * @param string $blockSubId
     * @return PageModule
     */
    public function setBlockSubId($blockSubId)
    {
        $this->blockSubId = $blockSubId;
    
        return $this;
    }

    /**
     * Get blockSubId
     *
     * @return string 
     */
    public function getBlockSubId()
    {
        return $this->blockSubId;
    }

    /**
     * Set forward
     *
     * @param string $forward
     * @return PageModule
     */
    public function setForward($forward)
    {
        $this->forward = $forward;
    
        return $this;
    }

    /**
     * Get forward
     *
     * @return string 
     */
    public function getForward()
    {
        return $this->forward;
    }

    /**
     * Add pages
     *
     * @param \Acme\PageBundle\Entity\Page $pages
     * @return PageModule
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