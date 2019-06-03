<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Usuario
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsuarioRepository")
 * @UniqueEntity(fields={"email"}, message="Correo asignado previamente a otro usuario")
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class Usuario extends BaseUser
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN= 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN=  'ROLE_SUPER_ADMIN';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombres", type="string", length=255, nullable=true)
     */
    private $nombres;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinTable(name="mtm_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Acceso")
     * @ORM\JoinTable(name="mtm_usuario_acceso",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="acceso_id", referencedColumnName="id")}
     * )
     */
    protected $accesos;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Permiso")
     * @ORM\JoinTable(name="mtm_usuario_permiso",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="permiso_id", referencedColumnName="id")}
     * )
     */
    protected $permisos;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\GrupoCambios")
     * @ORM\JoinTable(name="mtm_usuario_grupocambios",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="grupo_cambios_id", referencedColumnName="id")}
     * )
     */
    protected $grupocambios;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\GrupoAnteproyecto")
     * @ORM\JoinTable(name="mtm_usuario_grupoanteproyecto",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="grupo_anteproyecto_id", referencedColumnName="id")}
     * )
     */
    protected $grupoanteproyecto;

    /**
     * @var $proyectos
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Proyectogo", mappedBy="incharge")
     */
    private $proyectos;

    /**
     * @var $assitent
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Goassistent", mappedBy="assistent")
     */
    private $assistent;

    /**
     * @var $checker
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Gochecker", mappedBy="checker")
     */
    private $checker;

    /**
     * Usuario constructor.
     */
    public function __construct($valor)
    {
        parent::__construct();
        $this->username = 'username';
        if ($valor === 'nuevo') {
            $this->plainPassword = '123456';
        }
        $this->setEnabled(true);
        $this->proyectos = new ArrayCollection();
        $this->proyectogos= new ArrayCollection();
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * @param $nombres
     * @return $this
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param $apellidos
     * @return $this
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultRole()
    {
        return self::ROLE_USER;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccesos()
    {
        return $this->accesos ?: $this->accesos = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getAccesoNames()
    {
        $names = array();
        foreach ($this->getAccesos() as $acceso) {
            $names[] = $acceso->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAcceso($name)
    {
        return in_array($name, $this->getAccesoNames());
    }

    /**
     * {@inheritdoc}
     */
    public function addAcceso(Acceso $acceso)
    {
        if (!$this->getAccesos()->contains($acceso)) {
            $this->getAccesos()->add($acceso);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAcceso(Acceso $acceso)
    {
        if ($this->getAccesos()->contains($acceso)) {
            $this->getAccesos()->removeElement($acceso);
        }

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function getPermisos()
    {
        return $this->permisos ?: $this->permisos = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getPermisoNames()
    {
        $names = array();
        foreach ($this->getPermisos() as $permiso) {
            $names[] = $permiso->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPermiso($name)
    {
        return in_array($name, $this->getPermisoNames());
    }

    /**
     * {@inheritdoc}
     */
    public function addPermiso(Permiso $permiso)
    {
        if (!$this->getPermisos()->contains($permiso)) {
            $this->getPermisos()->add($permiso);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removePermiso(Permiso $permiso)
    {
        if ($this->getPermisos()->contains($permiso)) {
            $this->getPermisos()->removeElement($permiso);
        }

        return $this;
    }


    /**
     * Overriding Fos User class due to impossible to set default role ROLE_USER
     * @see User at line 138
     * @link https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Model/User.php#L138
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function getGrupoCambios()
    {
        return $this->grupoanteproyecto ?: $this->grupoanteproyecto = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getGrupoCambiosNames()
    {
        $names = array();
        foreach ($this->getGrupoCambios() as $grupoanteproyecto) {
            $names[] = $grupoanteproyecto->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasGrupoCambios($name)
    {
        return in_array($name, $this->getGrupoCambiosNames());
    }

    /**
     * {@inheritdoc}
     */
    public function addGrupoCambios(GrupoCambios $grupoanteproyecto)
    {
        if (!$this->getGrupoCambios()->contains($grupoanteproyecto)) {
            $this->getGrupoCambios()->add($grupoanteproyecto);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeGrupoCambios(GrupoCambios $grupoanteproyecto)
    {
        if ($this->getGrupoCambios()->contains($grupoanteproyecto)) {
            $this->getGrupoCambios()->removeElement($grupoanteproyecto);
        }

        return $this;
    }

    public function getFullName(){
        return $this->getNombres()." ".$this->getApellidos();
    }

    /**
     * {@inheritdoc}
     */
    public function getGrupoAnteproyecto()
    {
        return $this->grupoanteproyecto ?: $this->grupoanteproyecto = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getGrupoAnteproyectoNames()
    {
        $names = array();
        foreach ($this->getGrupoAnteproyecto() as $grupoanteproyecto) {
            $names[] = $grupoanteproyecto->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasGrupoAnteproyecto($name)
    {
        return in_array($name, $this->getGrupoAnteproyectoNames());
    }

    /**
     * {@inheritdoc}
     */
    public function addGrupoAnteproyecto(GrupoAnteproyecto $grupoanteproyecto)
    {
        if (!$this->getGrupoAnteproyecto()->contains($grupoanteproyecto)) {
            $this->getGrupoAnteproyecto()->add($grupoanteproyecto);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeGrupoAnteproyecto(GrupoAnteproyecto $grupoanteproyecto)
    {
        if ($this->getGrupoAnteproyecto()->contains($grupoanteproyecto)) {
            $this->getGrupoAnteproyecto()->removeElement($grupoanteproyecto);
        }

        return $this;
    }

    /**
     * Add proyecto
     *
     * @param Proyecto $proyecto
     *
     * @return Usuario
     */
    public function addProyecto(Proyecto $proyecto)
    {
        $this->proyectos[] = $proyecto;

        return $this;
    }

    /**
     * Remove proyecto
     *
     * @param Proyecto $proyecto
     */
    public function removeProyecto(Proyecto $proyecto)
    {
        $this->proyectos->removeElement($proyecto);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function fullName()
    {
        return $this->getNombres()." ".$this->getApellidos();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getNombres() . " " . $this->getApellidos();
    }

}
