<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Task
 *
 * @ORM\Table(name="taskassistent", uniqueConstraints={@ORM\UniqueConstraint(columns={"task", "assistent"})})
 * @ORM\Entity()
 * @UniqueEntity(fields={"task","assistent"}, message="Combinacion ya existe en la base de datos.")

 */
class Goassistent
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Taskdocument", inversedBy="task")
     * @ORM\JoinColumn(name="task",referencedColumnName="id")
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="assistent")
     * @ORM\JoinColumn(name="assistent",referencedColumnName="id")
     */
    private $assistent;

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

    

    public function __construct($usuario)
    {
        $this->createdby = $usuario;
        $this->createdat = new \DateTime();
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
     * Set task
     *
     * @param Taskdocument $task
     *
     * @return Goassistent
     */
    public function setTask(Taskdocument $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get Task
     *
     * @return Taskdocument
     */
    public function getTask()
    {
        return $this->task;
    }


    /**
     * Set assistent
     *
     * @param Usuario $assistent
     *
     * @return Goassistent
     */
    public function setAssistent(Usuario $assistent = null)
    {
        $this->assistent = $assistent;

        return $this;
    }

    /**
     * Get Assistent
     *
     * @return Usuario
     */
    public function getAssistent()
    {
        return $this->assistent;
    }
    
    /**
     * Set createdby
     *
     * @param Usuario $createdby
     *
     * @return Goassistent
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
    
}