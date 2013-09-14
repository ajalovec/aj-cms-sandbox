<?php
namespace Acme\ServicesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_services")
 * @ORM\Entity(repositoryClass="ServicesRepository")
 */
class Services
{
    /**
     * @ORM\Id()
     * @ORM\Column(type = "integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column()
     */
    protected $bodyFormatter;

    /**
     * @ORM\Column(type = "text", nullable=true)
     */
    protected $body;

    /**
     * @ORM\Column(type = "text", nullable=true)
     */
    protected $rawBody;
    

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
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
     * @return Services
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
     * Set description
     *
     * @param string $description
     * @return Services
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
     * Set bodyFormatter
     *
     * @param string $bodyFormatter
     * @return Services
     */
    public function setBodyFormatter($bodyFormatter)
    {
        $this->bodyFormatter = $bodyFormatter;
    
        return $this;
    }

    /**
     * Get bodyFormatter
     *
     * @return string 
     */
    public function getBodyFormatter()
    {
        return $this->bodyFormatter;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Services
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
     * Set rawBody
     *
     * @param string $rawBody
     * @return Services
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;
    
        return $this;
    }

    /**
     * Get rawBody
     *
     * @return string 
     */
    public function getRawBody()
    {
        return $this->rawBody;
    }
}