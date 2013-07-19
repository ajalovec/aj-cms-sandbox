<?php
// src/Acme/StoreBundle/Entity/Product.php
namespace Acme\FormBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="form")
 */
class Form
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=100, name="action", nullable=true)
     */
    protected $action;	
    /**
     * @ORM\Column(type="string", length=100, name="type", nullable=true)
     */
    protected $type;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $area;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $localization;
    /**
     * @ORM\Column(type="string", length=100, name="building_type", nullable=true)
     */
    protected $buildingType;		
    /**
     * @ORM\Column(type="integer", name="rooms_count", nullable=true)
     */
    protected $roomsCount;	
    /**
     * @ORM\Column(type="decimal", scale=2, name="price_max", nullable=true)
     */	
	protected $priceMax;
    /**
     * @ORM\Column(type="decimal", scale=2, name="price_per_meter_max", nullable=true)
     */	
	protected $pricePerMeterMax;     	
    /**
     * @ORM\Column(type="integer",  name="storey", nullable=true)
     */	
	protected $storey;  
    /**
     * @ORM\Column(type="string", length=100, name="block_type", nullable=true)
     */	
	protected $blockType;  	
    /**
     * @ORM\Column(type="string", length=100, name="standard", nullable=true)
     */	
	protected $standard;  		
    /**
     * @ORM\Column(type="string", length=100, name="property_type", nullable=true)
     */	
	protected $propertyType;  		
    /**
     * @ORM\Column(type="string", length=100, name="media", nullable=true)
     */	
	protected $media;  		
    /**
     * @ORM\Column(type="string", length=100, name="loading_ramp", nullable=true)
     */	
	protected $loadingRamp;  		
    /**
     * @ORM\Column(type="string", length=100, name="maneuver_field", nullable=true)
     */	
	protected $maneuverField;
    /**
     * @ORM\Column(type="string", length=100, name="email", nullable=true)
     */	
	protected $phone;
	/**
     * @ORM\Column(type="string", length=100, name="phone", nullable=true)
     */	
	protected $email;

	public function __toString(){
		return (string)$this->action;
	}
	public function getActionTranslator()
	{
		$action = $this->action;
		$actionTranslated = 'Nie podano';
		switch ($action) {
			case 'buy-property':
				$actionTranslated = 'Kupić nieruchomość';
				break;
			case 'rent-property':
				$actionTranslated = 'Wynająć nieruchomość';
				break;
			default:
				$actionTranslated = 'Nie podano';
				break;
		}
		return $actionTranslated;
	}
	public function getTypeTranslator()
	{
		$type = $this->type;
		$typeTranslated = 'Nie podano';
		switch ($type) {
			case 'hause':
				$typeTranslated = 'Dom';
				break;
			case 'flat':
				$typeTranslated = 'Mieszkanie';
				break;
			case 'local':
				$typeTranslated = 'Lokal użytkowy/biuro';
				break;
			case 'parcel':
				$typeTranslated = 'Nieruchomość gruntowa';
				break;
			case 'storage':
				$typeTranslated = 'Hala/Magazyn';
				break;
			default:
				$typeTranslated = 'Nie podano';
				break;
		}
		return $typeTranslated;
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
     * Set action
     *
     * @param string $action
     * @return Form
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Form
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
     * Set area
     *
     * @param float $area
     * @return Form
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return float 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set localization
     *
     * @param string $localization
     * @return Form
     */
    public function setLocalization($localization)
    {
        $this->localization = $localization;
    
        return $this;
    }

    /**
     * Get localization
     *
     * @return string 
     */
    public function getLocalization()
    {
        return $this->localization;
    }

    /**
     * Set buildingType
     *
     * @param string $buildingType
     * @return Form
     */
    public function setBuildingType($buildingType)
    {
        $this->buildingType = $buildingType;
    
        return $this;
    }

    /**
     * Get buildingType
     *
     * @return string 
     */
    public function getBuildingType()
    {
        return $this->buildingType;
    }

    /**
     * Set roomsCount
     *
     * @param integer $roomsCount
     * @return Form
     */
    public function setRoomsCount($roomsCount)
    {
        $this->roomsCount = $roomsCount;
    
        return $this;
    }

    /**
     * Get roomsCount
     *
     * @return integer 
     */
    public function getRoomsCount()
    {
        return $this->roomsCount;
    }

    /**
     * Set priceMax
     *
     * @param float $priceMax
     * @return Form
     */
    public function setPriceMax($priceMax)
    {
        $this->priceMax = $priceMax;
    
        return $this;
    }

    /**
     * Get priceMax
     *
     * @return float 
     */
    public function getPriceMax()
    {
        return $this->priceMax;
    }

    /**
     * Set pricePerMeterMax
     *
     * @param float $pricePerMeterMax
     * @return Form
     */
    public function setPricePerMeterMax($pricePerMeterMax)
    {
        $this->pricePerMeterMax = $pricePerMeterMax;
    
        return $this;
    }

    /**
     * Get pricePerMeterMax
     *
     * @return float 
     */
    public function getPricePerMeterMax()
    {
        return $this->pricePerMeterMax;
    }

    /**
     * Set storey
     *
     * @param integer $storey
     * @return Form
     */
    public function setStorey($storey)
    {
        $this->storey = $storey;
    
        return $this;
    }

    /**
     * Get storey
     *
     * @return integer 
     */
    public function getStorey()
    {
        return $this->storey;
    }

    /**
     * Set blockType
     *
     * @param string $blockType
     * @return Form
     */
    public function setBlockType($blockType)
    {
        $this->blockType = $blockType;
    
        return $this;
    }

    /**
     * Get blockType
     *
     * @return string 
     */
    public function getBlockType()
    {
        return $this->blockType;
    }

    /**
     * Set standard
     *
     * @param string $standard
     * @return Form
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;
    
        return $this;
    }

    /**
     * Get standard
     *
     * @return string 
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * Set propertyType
     *
     * @param string $propertyType
     * @return Form
     */
    public function setPropertyType($propertyType)
    {
        $this->propertyType = $propertyType;
    
        return $this;
    }

    /**
     * Get propertyType
     *
     * @return string 
     */
    public function getPropertyType()
    {
        return $this->propertyType;
    }

    /**
     * Set media
     *
     * @param string $media
     * @return Form
     */
    public function setMedia($media)
    {
        $this->media = $media;
    
        return $this;
    }

    /**
     * Get media
     *
     * @return string 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set loadingRamp
     *
     * @param string $loadingRamp
     * @return Form
     */
    public function setLoadingRamp($loadingRamp)
    {
        $this->loadingRamp = $loadingRamp;
    
        return $this;
    }

    /**
     * Get loadingRamp
     *
     * @return string 
     */
    public function getLoadingRamp()
    {
        return $this->loadingRamp;
    }

    /**
     * Set maneuverField
     *
     * @param string $maneuverField
     * @return Form
     */
    public function setManeuverField($maneuverField)
    {
        $this->maneuverField = $maneuverField;
    
        return $this;
    }

    /**
     * Get maneuverField
     *
     * @return string 
     */
    public function getManeuverField()
    {
        return $this->maneuverField;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Form
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Form
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}