<?php
namespace Acme\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_content")
 * @ORM\Entity(repositoryClass="ContentRepository")
 */
class Content
{
    /**
     * @ORM\Id()
     * @ORM\Column(type = "integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(length = 1000)
     *
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(length = 140, nullable = true)
     */
    protected $description;

    /**
     * @ORM\Column()
     */
    protected $bodyFormatter;

    /**
     * @ORM\Column(type = "text")
     */
    protected $body;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity = "Group", inversedBy = "contents")
     */
    protected $group;

    /**
     * @var Content
     *
     * @ORM\ManyToOne(targetEntity = "Content", inversedBy = "children")
     */
    protected $parent;

    protected $parents;

    /**
     * @var Content[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity = "Content", mappedBy = "parent")
     */
    protected $children;

    /**
     * @ORM\Column(type = "boolean", nullable = true)
     */
    protected $absolutePathFlag;

    /**
     * @ORM\Column(type = "integer")
     */
    protected $position = 1;


    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
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
     * @return string
     */
    public function getBodyFormatter()
    {
        return $this->bodyFormatter;
    }

    /**
     * @param string $rawBody
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;
    }

    /**
     * @return string
     */
    public function getRawBody()
    {
        return $this->rawBody;
    }

    /**
     * @param  string  $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string  $description
     *
     * @return self
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
     * @param  string  $body
     * @return self
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
     * @param  string  $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  boolean $absolutePathFlag
     *
     * @return self
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
    public function addChildren(Content $children)
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
    public function setParent(Content $parent = null)
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

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     *
     * @return self
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;

        return $this;
    }
}
