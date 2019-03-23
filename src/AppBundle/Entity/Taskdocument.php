<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Task
 *
 * @ORM\Table(name="taskdocument", uniqueConstraints={@ORM\UniqueConstraint(columns={"proyectogo", "document"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskdocumentRepository")
 * @UniqueEntity(fields={"proyectogo","document"}, message="Combinacion ya existe en la base de datos.")

 */
class Taskdocument
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Proyectogo", inversedBy="taskdocuments")
     * @ORM\JoinColumn(name="proyectogo",referencedColumnName="id")
     */
    private $proyectogo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Document", inversedBy="taskdocuments")
     * @ORM\JoinColumn(name="document",referencedColumnName="id")
     */
    private $document;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_completed", type="datetime",nullable=true)
     */
    private $dateCompleted;

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
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="createdat", type="datetime", nullable=true)
     */
    private $createdat;

    /**
     * @var Goestado
     *
     *  @ORM\ManyToOne(targetEntity="AppBundle\Entity\Goestado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado", referencedColumnName="id")
     * })
     */
    private $estado;

    /**e
     * @var string
     * @ORM\Column(name="linkfile", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var $checker
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Gochecker", mappedBy="task")
     */
    private $checkers;

    /**
     * @var $assistent
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Goassistent", mappedBy="task")
     */
    private $assistents;

    /**
     * @var $file
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Gofile", mappedBy="task")
     */
    private $file;

    /**
     * @var $asignedat
     * @var \DateTime
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="asignedat", type="datetime", nullable=true)
     */
    private $asignedat;

    /**
     * Taskdocument constructor.
     * @param $usuario
     * @param $proyecto
     * @param $document
     * @param $estado
     */
    public function __construct($usuario,$proyecto, $document, $estado)
    {
        $this->createdby = $usuario;
        $this->createdat = new \DateTime();
        $this->estado = $estado; // Pendiente
        $this->proyectogo = $proyecto;
        $this->document = $document;
        $this->assistents = new ArrayCollection();
        $this->checkers = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set proyectogo
     *
     * @param Proyectogo $proyectogo
     *
     * @return Taskdocument
     */
    public function setProyectogo(Proyectogo $proyectogo = null)
    {
        $this->proyectogo = $proyectogo;

        return $this;
    }

    /**
     * Get Proyectogo
     *
     * @return Proyectogo
     */
    public function getProyectogo()
    {
        return $this->proyectogo;
    }


    /**
     * Set document
     *
     * @param Document $document
     *
     * @return Taskdocument
     */
    public function setDocument(Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get Document
     *
     * @return Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param \DateTime $dateCompleted
     */
    public function setDateCompleted($dateCompleted)
    {
        $this->dateCompleted = $dateCompleted;
    }

    /**
     * @return \DateTime
     */
    public function getDateCompleted()
    {
        return $this->dateCompleted;
    }



    /**
     * Set createdby
     *
     * @param \AppBundle\Entity\Usuario $createdby
     *
     * @return Taskdocument
     */
    public function setCreatedby(Usuario $createdby = null)
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
     * @param \DateTime $createdat
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set estado
     *
     * @param Goestado $estado
     *
     * @return Taskdocument
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return Goestado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add checker
     *
     * @param Gochecker $checker
     *
     * @return Taskdocument
     */
    public function addChecker(Gochecker $checker)
    {
        $this->checkers[] = $checker;

        return $this;
    }

    /**
     * Remove checker
     *
     * @param Gochecker $checker
     */
    public function removeChecker(Gochecker $checker)
    {
        $this->checkers->removeElement($checker);
    }

    /**
     * Get checkers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCheckers()
    {
        return $this->checkers;
    }

    /**
     * Add assistent
     *
     * @param Goassistent $assistent
     *
     * @return Taskdocument
     */
    public function addAssistent (Goassistent $assistent)
    {
        $this->assistents[] = $assistent;

        return $this;
    }

    /**
     * Remove assistent
     *
     * @param Goassistent $assistent
     */
    public function removeAssistent (Goassistent $assistent)
    {
        $this->assistents->removeElement($assistent);
    }

    /**
     * Get assistents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssistents()
    {
        return $this->assistents;
    }
    

    /**
     * Set file
     *
     * @param Gofile $file
     *
     * @return Taskdocument
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return Gofile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set asignedat
     *
     * @param \DateTime $asignedat
     */
    public function setAsignedat($asignedat)
    {
        $this->asignedat = $asignedat;
    }

    /**
     * Get asignedat
     *
     * @return \DateTime
     */
    public function getAsignedat()
    {
        return $this->asignedat;
    }

}