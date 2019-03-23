<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Logmail
 *
 * @author Roberto Zuñiga <roberto.zuniga.araya@gmail.com>
 *
 *
 * @ORM\Table(name="logmail")
 *
 * @ORM\Entity
 */
class Logmail
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
     *   @ORM\JoinColumn(name="receiver", referencedColumnName="id")
     * })
     */
    private $receiver;

    public function __construct($usuario, $accion, $nombre)
    {
        $this->accion = $accion;
        $this->nombre = $nombre;
        $this->receiver = $usuario;
        $this->dateCreado = new \DateTime();
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
     * @return Logmail
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
     * Set dateCreado
     *
     * @param \DateTime $dateCreado
     *
     * @return Logmail
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
     * Set receiver
     *
     * @param Usuario $receiver
     *
     * @return Logmail
     */
    public function setReceiver(Usuario $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return Usuario
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
    
    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Logmail
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
}
