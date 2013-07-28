<?php
namespace Acme\PageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_route")
 */
class Route {
	
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
	 * Pattern - sciezka Route 
	 *
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    protected $pattern;

    /**
	 * Unikalny index dla sciezki 
	 *
     * @ORM\Column(name="route_name", type="string", length=100, nullable=true)
     */
    protected $routeName;

	 /**
	  * Kontroler
	  *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
	protected $controller;
	
    /**
	 * Seo tytul
	 *
     * @ORM\Column(name="seo_title", type="string", length=1000, nullable=true)
     */
    protected $seoTitle;	
	
    /**
	 * Seo description
	 *
     * @ORM\Column(name="seo_description", type="string", length=1000, nullable=true)
     */
    protected $seoDescription;	
	
    /**
	 * Seo keywords 
	 *
     * @ORM\Column(name="seo_key_words", type="string", length=1000, nullable=true)
     */
    protected $seoKeyWords;		
	
	/**
	 * Czy statyczna
	 *
	 * @ORM\Column(type="boolean", name="is_route_static", nullable=true)
	 */
    protected $isRouteStatic;
	
    public function __toString()
    {
        return $this->routeName;
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
     * Set pattern
     *
     * @param string $pattern
     * @return Route
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    
        return $this;
    }

    /**
     * Get pattern
     *
     * @return string 
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set routeName
     *
     * @param string $routeName
     * @return Route
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    
        return $this;
    }

    /**
     * Get routeName
     *
     * @return string 
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set controller
     *
     * @param string $controller
     * @return Route
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    
        return $this;
    }

    /**
     * Get controller
     *
     * @return string 
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     * @return Route
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    
        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string 
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     * @return Route
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    
        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string 
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set seoKeyWords
     *
     * @param string $seoKeyWords
     * @return Route
     */
    public function setSeoKeyWords($seoKeyWords)
    {
        $this->seoKeyWords = $seoKeyWords;
    
        return $this;
    }

    /**
     * Get seoKeyWords
     *
     * @return string 
     */
    public function getSeoKeyWords()
    {
        return $this->seoKeyWords;
    }

    /**
     * Set isRouteStatic
     *
     * @param boolean $isRouteStatic
     * @return Route
     */
    public function setIsRouteStatic($isRouteStatic)
    {
        $this->isRouteStatic = $isRouteStatic;
    
        return $this;
    }

    /**
     * Get isRouteStatic
     *
     * @return boolean 
     */
    public function getIsRouteStatic()
    {
        return $this->isRouteStatic;
    }
}