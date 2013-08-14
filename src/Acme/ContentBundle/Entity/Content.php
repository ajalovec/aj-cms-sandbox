<?php
namespace Acme\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_content")
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
     * @ORM\Column(type="string")
     */
    protected $bodyFormatter;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\Column(type="text")
     */
    protected $rawBody;
    
    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Content", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    protected $parents;
    
    /**
     * @ORM\OneToMany(targetEntity="Content", mappedBy="parent")
     */
    protected $children;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, name="absolute_path_flag")
     */
    protected $absolutePathFlag;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getTitle();
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
     * @param $contentFormatter
     */
    public function setBodyFormatter($bodyFormatter)
    {
        $this->bodyFormatter = $bodyFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getBodyFormatter()
    {
        return $this->bodyFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawBody()
    {
        return $this->rawBody;
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


    /**
     * {@inheritdoc}
     */
    public function addChildren(PageInterface $children)
    {
        $this->children[] = $children;

        $children->setParent($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setParent(PageInterface $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent($level = -1)
    {
        if (-1 === $level) {
            return $this->parent;
        }

        $parents = $this->getParents();

        if ($level < 0) {
            $level = count($parents) + $level;
        }

        return isset($parents[$level]) ? $parents[$level] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setParents(array $parents)
    {
        $this->parents = $parents;
    }

    /**
     * {@inheritdoc}
     */
    public function getParents()
    {
        if (!$this->parents) {

            $page    = $this;
            $parents = array();

            while ($page->getParent()) {
                $page      = $page->getParent();
                $parents[] = $page;
            }

            $this->setParents(array_reverse($parents));
        }

        return $this->parents;
    }

}