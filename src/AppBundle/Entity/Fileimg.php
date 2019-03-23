<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Class Fileimg
 *
 * @author Roberto Zuñiga <roberto.zuniga.araya@gmail.com>
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="fileimg")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileimgRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Fileimg
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var Category
     *
     *  @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     *
     * @Gedmo\Slug(fields={"created", "link"})
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
     * @ORM\ManyToOne(targetEntity="Fileimg", inversedBy="children")
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
     * @ORM\Column(type="boolean", name="activo", nullable=true)
     */
    private $activo;

    /**
     * @var Usuario
     *
     *  @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     * })
     */
    private $createdby;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Acceso")
     * @ORM\JoinTable(name="fileimg_acceso",
     *      joinColumns={@ORM\JoinColumn(name="fileimg_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="acceso_id", referencedColumnName="id")}
     * )
     */
    protected $accesos;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $enabled;

    /**
     * Fileimg constructor.
     * @param $usuario
     */
    public function __construct($usuario)
    {
        $this->children = new ArrayCollection();
        $this->dateCreado = new \DateTime();
        $this->createdby = $usuario;
        $this->enabled = true;
        
    }

    public function getSlug()
    {
        return $this->slug;
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
     * Set link
     *
     * @param string $link
     *
     * @return Fileimg
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     * @Assert\NotBlank(message="not_blank")
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }


    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Fileimg
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get Category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Fileimg
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
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
     * Set createdby
     *
     * @param \AppBundle\Entity\Usuario $createdby
     *
     * @return Fileimg
     */
    public function setCreatedby(\AppBundle\Entity\Usuario $createdby = null)
    {
        $this->createdby = $createdby;

        return $this;
    }

    /**
     * Get createdby
     *
     * @return \AppBundle\Entity\Usuario
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Category
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccesos()
    {
        return $this->accesos ?: $this->accesos = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getAccesoNames()
    {
        $names = array();
        foreach ($this->getAccesos() as $acceso) {
            $names[] = $acceso->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAcceso($name)
    {
        return in_array($name, $this->getAccesoNames());
    }

    /**
     * {@inheritdoc}
     */
    public function addAcceso(Acceso $acceso)
    {
        if (!$this->getAccesos()->contains($acceso)) {
            $this->getAccesos()->add($acceso);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAcceso(Acceso $acceso)
    {
        if ($this->getAccesos()->contains($acceso)) {
            $this->getAccesos()->removeElement($acceso);
        }

        return $this;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Fileimg
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
}
