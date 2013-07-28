<?php

// src/Acme/StoreBundle/Entity/Product.php
namespace Acme\FacebookBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_facebook_user")
 */
class FacebookUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="bigint", name="facebook_user_id")
     */
    protected $facebookUserId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $first_name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $last_name;
	
    /**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $birthday;
	
    /**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $gender;
	
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
	protected $timezone;
	
    /**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $locale;
	
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
	protected $verified;
	
    /**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $updated_time;

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
     * Set facebookUserId
     *
     * @param integer $facebookUserId
     * @return FacebookUser
     */
    public function setFacebookUserId($facebookUserId)
    {
        $this->facebookUserId = $facebookUserId;
    
        return $this;
    }

    /**
     * Get facebookUserId
     *
     * @return integer 
     */
    public function getFacebookUserId()
    {
        return $this->facebookUserId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return FacebookUser
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
     * Set first_name
     *
     * @param string $firstName
     * @return FacebookUser
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    
        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return FacebookUser
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    
        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     * @return FacebookUser
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    /**
     * Get birthday
     *
     * @return string 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return FacebookUser
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set timezone
     *
     * @param integer $timezone
     * @return FacebookUser
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    
        return $this;
    }

    /**
     * Get timezone
     *
     * @return integer 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return FacebookUser
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     * @return FacebookUser
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    
        return $this;
    }

    /**
     * Get verified
     *
     * @return boolean 
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set updated_time
     *
     * @param string $updatedTime
     * @return FacebookUser
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updated_time = $updatedTime;
    
        return $this;
    }

    /**
     * Get updated_time
     *
     * @return string 
     */
    public function getUpdatedTime()
    {
        return $this->updated_time;
    }
}