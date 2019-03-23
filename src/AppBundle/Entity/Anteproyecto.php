<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Anteproyecto
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="anteproyecto")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnteproyectoRepository")
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 *
 */
class Anteproyecto
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
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="fecha_admision", type="date", nullable=true)
     */
    private $dateadmision;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion_uno", type="text", nullable=true)
     */
    private $observationone;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion_dos", type="text", nullable=true)
     */
    private $observationtwo;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="resolucion", type="string", length=255, nullable=false)
     */
    private $resolution;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="fecha_resolucion", type="date", nullable=true)
     */
    private $dateresolution;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="fecha_caducidad", type="date", nullable=true)
     */
    private $dateexpiration;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="comentarios", type="text", nullable=false)
     */
    private $comments;

    /**
     * @var Comuna
     * @Assert\NotBlank(message="not_blank")
     *  @ORM\ManyToOne(targetEntity="Comuna")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="comuna", referencedColumnName="id")
     * })
    */
     private $comuna;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="revisor", type="string", length=255, nullable=false)
     */
    private $revisor;


    /**
     * @var Reviser
     * @Assert\NotBlank(message="not_blank")
     *  @ORM\ManyToOne(targetEntity="Reviser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="revisor", referencedColumnName="id")
     * })

    private $reviser;

    /**
     * @var Observation
     * @Assert\NotBlank(message="not_blank")
     *  @ORM\ManyToOne(targetEntity="Observation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="observacion", referencedColumnName="id")
     * })

    private $observacion;
    */

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $enabled;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="createdat", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var Usuario
     *
     *  @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @ORM\Column(type="string", name="file", nullable=true)
     *
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "por favor subir un file de PDF valido"
     * )
     */
    private $file;

    /**e
     * @var string
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * Anteproyecto constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->createdAt = new \DateTime();
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
     * @return Anteproyecto
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
     * Get dateadmision
     *
     * @return \DateTime
     */
    public function getDateadmision()
    {
        return $this->dateadmision;
    }

    /**
     * Set dateadmision
     *
     * @param \DateTime $dateadmision
     *
     * @return Anteproyecto
     */
    public function setDateadmision($dateadmision)
    {
        $this->dateadmision = $dateadmision;

        return $this;
    }

    /**
     * Get observationone
     *
     * @return string
     */
    public function getObservationone()
    {
        return $this->observationone;
    }

    /**
     * Set observationone
     *
     * @param string $observationone
     *
     * @return Anteproyecto
     */
    public function setObservationone($observationone)
    {
        $this->observationone = $observationone;

        return $this;
    }

    /**
     * Get observationtwo
     *
     * @return string
     */
    public function getObservationtwo()
    {
        return $this->observationtwo;
    }

    /**
     * Set observationtwo
     *
     * @param string $observationtwo
     *
     * @return Anteproyecto
     */
    public function setObservationtwo($observationtwo)
    {
        $this->observationtwo = $observationtwo;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set resolution
     *
     * @param string $resolution
     *
     * @return Anteproyecto
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get dateresolution
     *
     * @return \DateTime
     */
    public function getDateresolution()
    {
        return $this->dateresolution;
    }

    /**
     * Set dateresolution
     *
     * @param \DateTime $dateresolution
     *
     * @return Anteproyecto
     */
    public function setDateresolution($dateresolution)
    {
        $this->dateresolution = $dateresolution;

        return $this;
    }

    /**
     * Get dateexpiration
     *
     * @return \DateTime
     */
    public function getDateexpiration()
    {
        return $this->dateexpiration;
    }

    /**
     * Set dateexpiration
     *
     * @param \DateTime $dateexpiration
     *
     * @return Anteproyecto
     */
    public function setDateexpiration($dateexpiration)
    {
        $this->dateexpiration = $dateexpiration;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Anteproyecto
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comuna
     *
     * @return Comuna
    */
    public function getComuna()
    {
    return $this->comuna;
    }

    /**
     * Set comuna
     *
     * @param Reviser $comuna
     *
     * @return Anteproyecto
    */
    public function setComuna($comuna)
    {
    $this->comuna = $comuna;

    return $this;
    }

    /**
     * Get reviser
     *
     * @return Reviser

    public function getReviser()
    {
    return $this->reviser;
    }

    /**
     * Set reviser
     *
     * @param Reviser $reviser
     *
     * @return Anteproyecto

    public function setReviser($reviser)
    {
    $this->reviser = $reviser;

    return $this;
    }

    /**
     * Get observacion
     *
     * @return Observation

    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set observacion
     *
     * @param Observation $observacion
     *
     * @return Anteproyecto

    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }
    */

    /**
     * Set revisor
     *
     * @param string $revisor
     *
     * @return Anteproyecto
     */
    public function setRevisor($revisor)
    {
        $this->revisor = $revisor;

        return $this;
    }

    /**
     * Get revisor
     *
     * @return string
     */
    public function getRevisor()
    {
        return $this->revisor;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Anteproyecto
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Anteproyecto
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdBy
     *
     * @param Usuario $createdBy
     *
     * @return Anteproyecto
     */
    public function setCreatedBy(Usuario $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return Usuario
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Anteproyecto
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

}
