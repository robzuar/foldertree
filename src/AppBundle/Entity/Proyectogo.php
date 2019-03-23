<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Proyectogo
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="proyectogo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProyectogoRepository")
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 *
 */
class Proyectogo
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     *
     * @ORM\Column(name="inmobiliaria", type="string", length=255, nullable=false)
     */
    private $inmobiliaria;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startedat", type="datetime", nullable=true)
     */
    private $startedat;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taskdocument", mappedBy="proyectogo")
     */
    private $taskdocuments;

    /**
     * Proyectogo constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->getName();
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
     *
     * @return Proyectogo
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
     * Set inmobiliaria
     *
     * @param string $inmobiliaria
     *
     * @return Proyectogo
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Proyectogo
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
     * Set startedat
     *
     * @param \DateTime $startedat
     * */
    public function setStartedat($startedat)
    {
        $this->startedat = $startedat;
    }

    /**
     * Get startedat
     *
     * @return \DateTime
     */
    public function getStartedat()
    {
        return $this->startedat;
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

}
