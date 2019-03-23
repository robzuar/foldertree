<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Document
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DocumentRepository")
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class Document
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taskdocument", mappedBy="document")
     */
    private $taskdocuments;

    /**
     *
     * @Gedmo\Slug(fields={"created", "name"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;
    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;
    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;
    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;
    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;
    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="parent")
     */
    private $children;
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(name= "isfile", type="boolean")
     */
    private $isfile;

    /**
     * @var integer
     *
     * @ORM\Column(name="numfiles", type="integer", nullable=true)
     */
    private $numfiles;

    /**
     * Document constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->enabled = true;
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
     * @return Document
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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Document
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
     * @return mixed
     */
    public function getTaskdocuments()
    {
        return $this->taskdocuments;
    }

    /**
     * @param mixed $taskdocuments
     */
    public function setTaskdocuments($taskdocuments)
    {
        $this->taskdocuments = $taskdocuments;
    }

    /**
     * Set isfile
     *
     * @param boolean $isfile
     *
     * @return Document
     */
    public function setIsfile($isfile)
    {
        $this->isfile = $isfile;

        return $this;
    }

    /**
     * Get isfile
     *
     * @return boolean
     */
    public function getIsfile()
    {
        return $this->isfile;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
    public function getParent()
    {
        return $this->parent;
    }
    public function getRoot()
    {
        return $this->root;
    }
    public function getLevel()
    {
        return $this->level;
    }
    public function getChildren()
    {
        return $this->children;
    }
    public function getLeft()
    {
        return $this->lft;
    }
    public function getRight()
    {
        return $this->rgt;
    }
    public function getCreated()
    {
        return $this->created;
    }
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set numfiles
     *
     * @param integer $numfiles
     *
     * @return Document
     */
    public function setNumfiles($numfiles)
    {
        $this->numfiles = $numfiles;

        return $this;
    }

    /**
     * Get numfiles
     *
     * @return integer
     */
    public function getNumfiles()
    {
        return $this->numfiles;
    }
}
