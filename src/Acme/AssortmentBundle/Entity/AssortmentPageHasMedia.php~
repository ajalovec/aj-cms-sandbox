<?php
namespace Acme\AssortmentBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="assortment_page_has_media")
 */
class AssortmentPageHasMedia
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
	 */
    protected $media;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Acme\AssortmentBundle\Entity\AssortmentPage", inversedBy="assortmentPageHasMedias")
	 */
    protected $assortmentPage;
	
	/**
	 * @ORM\Column(type="integer")
	 */
    protected $position;

	/**
	 * @ORM\Column(type="boolean")
	 */
    protected $enabled;

    public function __construct()
    {
        $this->position = 0;
        $this->enabled  = false;
    }
    public function __toString()
    {
        return $this->getAssortmentPage().' | '.$this->getMedia();
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
     * Set position
     *
     * @param integer $position
     * @return AssortmentPageHasMedia
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return AssortmentPageHasMedia
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set media
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $media
     * @return AssortmentPageHasMedia
     */
    public function setMedia(\Application\Sonata\MediaBundle\Entity\Media $media = null)
    {
        $this->media = $media;
    
        return $this;
    }

    /**
     * Get media
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set assortmentPage
     *
     * @param \Acme\AssortmentBundle\Entity\AssortmentPage $assortmentPage
     * @return AssortmentPageHasMedia
     */
    public function setAssortmentPage(\Acme\AssortmentBundle\Entity\AssortmentPage $assortmentPage = null)
    {
        $this->assortmentPage = $assortmentPage;
    
        return $this;
    }

    /**
     * Get assortmentPage
     *
     * @return \Acme\AssortmentBundle\Entity\AssortmentPage 
     */
    public function getAssortmentPage()
    {
        return $this->assortmentPage;
    }
}