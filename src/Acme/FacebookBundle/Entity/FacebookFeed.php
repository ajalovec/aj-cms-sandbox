<?php
namespace Acme\FacebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="facebook_feed")
 *
 * Facebook feeds entity class
 */
class FacebookFeed 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    protected $link;
	
    /**
     * @ORM\Column(type="string", length=2048)
     */
    protected $picture;
	
    /**
     * @ORM\Column(type="string", length=1000)
     */
    protected $name;
	
    /**
     * @ORM\Column(type="string", length=1000)
     */
    protected $caption;
	
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
	
    /**
     * @ORM\Column(type="string", length=1000, name="feed_type")
     */
    protected $feedType;
	
	public function __toString()
	{
		return $this->name;
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
     * Set link
     *
     * @param string $link
     * @return FacebookFeed
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return FacebookFeed
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return FacebookFeed
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
     * Set caption
     *
     * @param string $caption
     * @return FacebookFeed
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return FacebookFeed
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set feedType
     *
     * @param string $feedType
     * @return FacebookFeed
     */
    public function setFeedType($feedType)
    {
        $this->feedType = $feedType;
    
        return $this;
    }

    /**
     * Get feedType
     *
     * @return string 
     */
    public function getFeedType()
    {
        return $this->feedType;
    }
}