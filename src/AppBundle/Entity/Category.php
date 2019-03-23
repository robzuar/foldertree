<?php
namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="category")
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * 
     * @ORM\Column(length=64)
     */
    private $title;
    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * 
     * @Gedmo\Slug(fields={"created", "title"})
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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
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
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
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
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="first", nullable=true)
     */
    private $first;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="proyecto", nullable=true)
     */
    private $proyecto;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Permiso")
     * @ORM\JoinTable(name="mtm_category_permiso",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="permiso_id", referencedColumnName="id")}
     * )
     */
    protected $permisos;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="imagine", nullable=true)
     */
    private $imagine;

    /**
     * @var Usuario
     * @Assert\NotBlank(message="not_blank")
     *  @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="encargado", referencedColumnName="id")
     * })
     */
    private $encargado;


    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->enabled = true;

    }

    public function getSlug()
    {
        return $this->slug;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
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
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set first
     *
     * @param boolean $first
     *
     * @return Category
     */
    public function setFirst($first)
    {
        $this->first = $first;

        return $this;
    }

    /**
     * Get first
     *
     * @return boolean
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * Set proyecto
     *
     * @param boolean $proyecto
     *
     * @return Category
     */
    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return boolean
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Category
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     *
     * @return Category
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Category
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Category
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Category
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\Category $child
     *
     * @return Category
     */
    public function addChild(\AppBundle\Entity\Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Category $child
     */
    public function removeChild(\AppBundle\Entity\Category $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * {@inheritdoc}
     */
    public function getPermisos()
    {
        return $this->permisos ?: $this->permisos = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getPermisoNames()
    {
        $names = array();
        foreach ($this->getPermisos() as $permiso) {
            $names[] = $permiso->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermiso($name)
    {
        return in_array($name, $this->getPermisoNames());
    }

    /**
     * {@inheritdoc}
     */
    public function addPermiso(Permiso $permiso)
    {
        if (!$this->getPermisos()->contains($permiso)) {
            $this->getPermisos()->add($permiso);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removePermiso(Permiso $permiso)
    {
        if ($this->getPermisos()->contains($permiso)) {
            $this->getPermisos()->removeElement($permiso);
        }

        return $this;
    }

    /**
     * Set imagine
     *
     * @param boolean $imagine
     *
     * @return Category
     */
    public function setImagine($imagine)
    {
        $this->imagine = $imagine;

        return $this;
    }

    /**
     * Get imagine
     *
     * @return boolean
     */
    public function getImagine()
    {
        return $this->imagine;
    }

    /**
     * Set encargado
     *
     * @param Usuario $encargado
     *
     * @return Category
     */
    public function setEncargado(Usuario $encargado = null)
    {
        $this->encargado = $encargado;

        return $this;
    }

    /**
     * Get encargado
     *
     * @return Usuario
     */
    public function getEncargado()
    {
        return $this->encargado;
    }

}
