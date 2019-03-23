<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Log
 *
 * @author Roberto Zuñiga <roberto.zuniga.araya@gmail.com>
 *
 *
 * @ORM\Table(name="log")
 *
 * @ORM\Entity
 */
class Log
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="accion", type="string", length=255, nullable=true)
     */
    private $accion;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="entidad", type="string", length=255, nullable=true)
     */
    private $entidad;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="date_creado", type="datetime", nullable=true)
     */
    private $dateCreado;

    /**
     * @var Usuario
     * @Assert\NotBlank(message="not_blank")
     *  @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_create", referencedColumnName="id")
     * })
     */
    private $usuarioCreate;

    /**
     * @var integer
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="entidad_id", type="integer", nullable=true)
     */
    private $entidadId;


    public function __construct($usuario)
    {
        $this->dateCreado = new \DateTime();
        $this->usuarioCreate = $usuario;
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
     * Set accion
     *
     * @param string $accion
     *
     * @return Log
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return string
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set entidad
     *
     * @param string $entidad
     *
     * @return Log
     */
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;

        return $this;
    }

    /**
     * Get entidad
     *
     * @return string
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set dateCreado
     *
     * @param \DateTime $dateCreado
     *
     * @return Log
     */
    public function setDateCreado($dateCreado)
    {
        $this->dateCreado = $dateCreado;

        return $this;
    }

    /**
     * Get dateCreado
     *
     * @return \DateTime
     */
    public function getDateCreado()
    {
        return $this->dateCreado;
    }

    /**
     * Set usuarioCreate
     *
     * @param Usuario $usuarioCreate
     *
     * @return Log
     */
    public function setUsuarioCreate(Usuario $usuarioCreate = null)
    {
        $this->usuarioCreate = $usuarioCreate;

        return $this;
    }

    /**
     * Get usuarioCreate
     *
     * @return Usuario
     */
    public function getUsuarioCreate()
    {
        return $this->usuarioCreate;
    }

    /**
     * Set entidadId
     *
     * @param integer $entidadId
     *
     * @return Log
     */
    public function setEntidadId($entidadId)
    {
        $this->entidadId = $entidadId;

        return $this;
    }

    /**
     * Get entidadId
     *
     * @return integer
     */
    public function getEntidadId()
    {
        return $this->entidadId;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Log
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Log
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
