<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoteRepository")
 */
class Note
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdat;


    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="notes")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $createdby;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Taskdocument", inversedBy="notes")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;

    public function __construct($usuario)
    {
        $this->createdby = $usuario;
        $this->createdat = new \DateTime();
        //$this->proyecto = new ArrayCollection();
        //$this->document = new ArrayCollection();
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
     * Set createdat
     *
     * @param \DateTime $createdat
     *
     * @return Note
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
     * Set createdby
     *
     * @param integer $createdby
     *
     * @return Note
     */
    public function setCreatedby($createdby)
    {
        $this->createdby = $createdby;

        return $this;
    }

    /**
     * Get createdby
     *
     * @return int
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Note
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }



    /**
     * Set task
     *
     * @param Taskdocument $task
     *
     * @return Note
     */
    public function setTask(Taskdocument $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return Taskdocument
     */
    public function getTask()
    {
        return $this->task;
    }
}
