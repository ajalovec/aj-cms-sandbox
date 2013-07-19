<?php

namespace Acme\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Acme\NewsletterBundle\Entity\NewsletterRepository")
 * @ORM\Table(name="newsletter")
 */
class Newsletter 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;	
	/**
	* @ORM\Column(type="string")
	*/
	protected $subject;
	
	/**
	* @ORM\Column(type="text")
	*/
	protected $body;	
	
	/**
	* @ORM\Column(type="datetime", name="send_time", nullable=true)
	*/
	protected $sendTime;
	
	public function __toString()
	{
		return (string)$this->subject;
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
     * Set subject
     *
     * @param string $subject
     * @return Newsletter
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Newsletter
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
     * Set sendTime
     *
     * @param \DateTime $sendTime
     * @return Newsletter
     */
    public function setSendTime($sendTime)
    {
        $this->sendTime = $sendTime;
    
        return $this;
    }

    /**
     * Get sendTime
     *
     * @return \DateTime 
     */
    public function getSendTime()
    {
        return $this->sendTime;
    }
}