<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use Gedmo\Loggable\Entity\LogEntry as Log;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository", readOnly=false)
 * @ORM\Table(
 *      name="log_proyecto",
 *      indexes={
 *          @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *          @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *          @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      }
 * )
 */
class LogProyecto extends Log
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
     * @var Usuario
     *
     *  @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdby", referencedColumnName="id")
     * })
     */
    private $createdby;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="enabled", nullable=true)
     */
    private $sendit;




    /**
     * Set createdby
     *
     * @param \AppBundle\Entity\Usuario $createdby
     *
     * @return LogProyecto
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
     * Set sendit
     *
     * @param boolean $sendit
     *
     * @return LogProyecto
     */
    public function setSendit($sendit = false)
    {
        $this->sendit = $sendit;

        return $this;
    }

    /**
     * Get sendit
     *
     * @return boolean
     */
    public function getSendit()
    {
        return $this->sendit;
    }
}
