<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Filego
 *
 * @author Roberto Zuñiga <roberto.zuniga.araya@gmail.com>
 *
 * @ORM\Table(name="Filego")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FilegoRepository")
 */
class Gofile
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
     * @var Taskdocument
     *
     *  @ORM\ManyToOne(targetEntity="Taskdocument")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task", referencedColumnName="id")
     * })
     */
    private $task;

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
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdat;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $enabled;

    /**
     * Gofile constructor.
     * @param $usuario
     * @param $task
     */
    public function __construct($usuario, $task)
    {
        $this->createdat = new \DateTime();
        $this->createdby = $usuario;
        $this->enabled = true;
        $this->task = $task;

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
     * Set file
     *
     * @param string $file
     *
     * @return Gofile
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     * @Assert\NotBlank(message="not_blank")
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Gofile
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
     * Set task
     *
     * @param Taskdocument $task
     *
     * @return Gofile
     */
    public function setTaskdocument(Taskdocument $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get Taskdocument
     *
     * @return Taskdocument
     */
    public function getTaskdocument()
    {
        return $this->task;
    }



    /**
     * Set createdby
     *
     * @param Usuario $createdby
     *
     * @return Gofile
     */
    public function setCreatedby(Usuario $createdby = null)
    {
        $this->createdby = $createdby;

        return $this;
    }

    /**
     * Get createdby
     *
     * @return Usuario
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     *
     * @return Gofile
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * Get createdat
     *
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }
    
    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Gofile
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
