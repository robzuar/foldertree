<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Proyecto
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="proyecto")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProyectoRepository")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogProyecto")
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 *
 */
class Proyecto
{
    /**
     * @@var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="inmobiliaria", type="string", length=255, nullable=false)
     */
    private $inmobiliaria;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=false)
     */
    private $direccion;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Length(min="12", max="15")
     *
     * @ORM\Column(name="rut", type="string", length=15, nullable=false)
     */
    private $rut;

    /**
     * @var integer
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="pisos", type="integer", nullable=false)
     */
    private $pisos;

    /**
     * @var integer
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="subterraneos", type="integer", nullable=false)
     */
    private $subterraneos;

    /**
     * @var integer
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="estacionamientos_vendibles", type="integer", nullable=false)
     */
    private $estacionamientos;

    /**
     * @var integer
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="estacionamientos_visita", type="integer", nullable=false)
     */
    private $estacionamientosVisita;

    /**
     * @var integer
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="departamentos", type="integer", nullable=false)
     */
    private $departamentos;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="proyectos")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @var integer
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="bodegas", type="integer", nullable=false)
     */
    private $bodegas;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     * @Gedmo\Versioned
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $dateend;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     * @Gedmo\Versioned
     * @ORM\Column(name="fecha_escritura", type="date", nullable=true)
     */
    private $dateescritura;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="supervisor", type="string", length=255, nullable=false)
     */
    private $supervisor;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="arquitecto", type="string", length=255, nullable=false)
     */
    private $arquitecto;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="estado", type="string", length=255, nullable=false)
     */
    private $estado;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $enabled;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @Gedmo\Versioned
     * @ORM\Column(name="datedelivery", type="string", length=255, nullable=false)
     */
    private $datedelivery;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     * @Gedmo\Versioned
     * @ORM\Column(name="fecha_ultima_cuota", type="date", nullable=true)
     */
    private $datelast;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha_termino_text", type="string", length=255, nullable=false)
     */
    private $dateendtext;



    /**
     * Proyecto constructor.
     */
    public function __construct()
    {
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Proyecto
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
     * Set inmobiliaria
     *
     * @param string $inmobiliaria
     *
     * @return Proyecto
     */
    public function setInmobiliaria($inmobiliaria)
    {
        $this->inmobiliaria = $inmobiliaria;

        return $this;
    }

    /**
     * Get inmobiliaria
     *
     * @return string
     */
    public function getInmobiliaria()
    {
        return $this->inmobiliaria;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Proyecto
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set rut
     *
     * @param string $rut
     *
     * @return Proyecto
     */
    public function setRut($rut)
    {
        $this->rut = $rut;

        return $this;
    }

    /**
     * Get rut
     *
     * @return string
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set pisos
     *
     * @param integer $pisos
     *
     * @return Proyecto
     */
    public function setPisos($pisos)
    {
        $this->pisos = $pisos;

        return $this;
    }

    /**
     * Get pisos
     *
     * @return integer
     */
    public function getPisos()
    {
        return $this->pisos;
    }

    /**
     * Set subterraneos
     *
     * @param integer $subterraneos
     *
     * @return Proyecto
     */
    public function setSubterraneos($subterraneos)
    {
        $this->subterraneos = $subterraneos;

        return $this;
    }

    /**
     * Get subterraneos
     *
     * @return integer
     */
    public function getSubterraneos()
    {
        return $this->subterraneos;
    }

    /**
     * Set estacionamientos
     *
     * @param integer $estacionamientos
     *
     * @return Proyecto
     */
    public function setEstacionamientos($estacionamientos)
    {
        $this->estacionamientos = $estacionamientos;

        return $this;
    }

    /**
     * Get estacionamientos
     *
     * @return integer
     */
    public function getEstacionamientos()
    {
        return $this->estacionamientos;
    }

    /**
     * Set estacionamientosVisita
     *
     * @param integer $estacionamientosVisita
     *
     * @return Proyecto
     */
    public function setEstacionamientosVisita($estacionamientosVisita)
    {
        $this->estacionamientosVisita = $estacionamientosVisita;

        return $this;
    }

    /**
     * Get estacionamientosVisita
     *
     * @return integer
     */
    public function getEstacionamientosVisita()
    {
        return $this->estacionamientosVisita;
    }

    /**
     * Set departamentos
     *
     * @param integer $departamentos
     *
     * @return Proyecto
     */
    public function setDepartamentos($departamentos)
    {
        $this->departamentos = $departamentos;

        return $this;
    }

    /**
     * Get departamentos
     *
     * @return integer
     */
    public function getDepartamentos()
    {
        return $this->departamentos;
    }

    /**
     * Set bodegas
     *
     * @param integer $bodegas
     *
     * @return Proyecto
     */
    public function setBodegas($bodegas)
    {
        $this->bodegas = $bodegas;

        return $this;
    }

    /**
     * Get bodegas
     *
     * @return integer
     */
    public function getBodegas()
    {
        return $this->bodegas;
    }

    /**
     * Set dateend
     *
     * @param \DateTime $dateend
     *
     * @return Proyecto
     */
    public function setDateend($dateend)
    {
        $this->dateend = $dateend;

        return $this;
    }

    /**
     * Get dateend
     *
     * @return \DateTime
     */
    public function getDateend()
    {
        return $this->dateend;
    }

    /**
     * Set dateescritura
     *
     * @param \DateTime $dateescritura
     *
     * @return Proyecto
     */
    public function setDateescritura($dateescritura)
    {
        $this->dateescritura = $dateescritura;

        return $this;
    }

    /**
     * Get dateescritura
     *
     * @return \DateTime
     */
    public function getDateescritura()
    {
        return $this->dateescritura;
    }

    /**
     * Set supervisor
     *
     * @param string $supervisor
     *
     * @return Proyecto
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return string
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * Set arquitecto
     *
     * @param string $arquitecto
     *
     * @return Proyecto
     */
    public function setArquitecto($arquitecto)
    {
        $this->arquitecto = $arquitecto;

        return $this;
    }

    /**
     * Get arquitecto
     *
     * @return string
     */
    public function getArquitecto()
    {
        return $this->arquitecto;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Proyecto
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Proyecto
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
     * Set category
     *
     * @param Category $category
     *
     * @return Proyecto
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set datedelivery
     *
     * @param string $datedelivery
     *
     * @return Proyecto
     */
    public function setDatedelivery($datedelivery)
    {
        $this->datedelivery = $datedelivery;

        return $this;
    }

    /**
     * Get datedelivery
     *
     * @return string
     */
    public function getDatedelivery()
    {
        return $this->datedelivery;
    }

    /**
     * Set datelast
     *
     * @param \DateTime $datelast
     *
     * @return Proyecto
     */
    public function setDatelast($datelast)
    {
        $this->datelast = $datelast;

        return $this;
    }

    /**
     * Get datelast
     *
     * @return \DateTime
     */
    public function getDatelast()
    {
        return $this->datelast;
    }



    /**
     * Set dateendtext
     *
     * @param string $dateendtext
     *
     * @return Proyecto
     */
    public function setDateendtext($dateendtext)
    {
        $this->dateendtext = $dateendtext;

        return $this;
    }

    /**
     * Get dateendtext
     *
     * @return string
     */
    public function getDateendtext()
    {
        return $this->dateendtext;
    }
}
