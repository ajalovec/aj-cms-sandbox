<?php
namespace Acme\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="content")
 */
class Content
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=140)
     */
    protected $description;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;
	
    /**
     * @ORM\Column(type="string", length=140)
     */
    protected $type;
	
    /**
     * @ORM\Column(type="boolean", nullable=true, name="absolute_path_flag")
     */
    protected $absolutePathFlag;
	
	public function __toString()
	{
		return $this->getTitle();
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
     * @return Content
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
     * Set description
     *
     * @param string $description
     * @return Content
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
     * Set body
     *
     * @param string $body
     * @return Content
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
     * Set type
     *
     * @param string $type
     * @return Content
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
     * Set absolutePathFlag
     *
     * @param boolean $absolutePathFlag
     * @return Content
     */
    public function setAbsolutePathFlag($absolutePathFlag)
    {
        $this->absolutePathFlag = $absolutePathFlag;
    
        return $this;
    }

    /**
     * Get absolutePathFlag
     *
     * @return boolean 
     */
    public function getAbsolutePathFlag()
    {
        return $this->absolutePathFlag;
    }
}