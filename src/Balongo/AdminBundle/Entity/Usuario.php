<?php

namespace Balongo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Usuario
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(
 * 	fields={"email"},
 * 	message="Este email ya existe en nuestra base de datos."
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Usuario implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=127, nullable=false, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=127, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=127, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=63, nullable=false)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=127, nullable=false)
     * @Assert\NotBlank()
     */
    private $apellidos;

    /**
     * @var integer
     *
     * @ORM\Column(name="rol", type="integer", nullable=false)
     */
    private $rol;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Balongo\AdminBundle\Entity\Comunidad", inversedBy="usuarios", cascade="remove")
     * @ORM\JoinTable(name="usuarios_comunidades")
     */
    private $comunidades;

    /**
     * @var boolean
     *
     * @ORM\Column(name="emailing", type="boolean", nullable=false)
     */
    private $emailing;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_alta", type="datetime", nullable=false)
     */
    private $fechaAlta;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="activo", type="boolean", nullable=false)
     */
    private $activo;  
    
    
    public function __construct()
    {
		$this->comunidades = new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set rol
     *
     * @param integer $rol
     * @return Usuario
     */
    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return integer 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set comunidades
     *
     * @param array $comunidades
     * @return Usuario
     */
    public function setComunidades($comunidades)
    {
        $this->comunidades = $comunidades;

        return $this;
    }

    /**
     * Get comunidades
     *
     * @return array 
     */
    public function getComunidades()
    {
        return $this->comunidades;
    }

    /**
     * Set emailing
     *
     * @param boolean $emailing
     * @return Usuario
     */
    public function setEmailing($emailing)
    {
        $this->emailing = $emailing;

        return $this;
    }

    /**
     * Get emailing
     *
     * @return boolean 
     */
    public function getEmailing()
    {
        return $this->emailing;
    }
    
    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     * @return Usuario
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime 
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }
    
    
    public function __toString()
    {
    	return $this->getNombre().' '.$this->getApellidos().' - '.$this->getEmail();
    }
    
    /**
     * Prepersist
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setFechaAlta( new \DateTime() );
        $this->setEmailing( true );
        $this->setActivo( false );
    }
    
    /*
     * 
     * 		FUNCIONES DE LA INTERFAZ USERINTERFACE
     * 
     */
    public function eraseCredentials() { }
    
    public function getRoles()
    {
    	if ( !$this->activo )
    		return array('IS_AUTHENTICATED_ANONYMOUSLY');
    	else
		 	switch ($this->rol) {
		 		case 1: 		return array('ROLE_ADMIN'); 							break;
		 		case 2: 		return array('ROLE_USER');								break;
		 		default:		return array('IS_AUTHENTICATED_ANONYMOUSLY');	break;
		 	}
    }
    
    public function getUsername()
    {
    	return $this->getEmail();
    }
     
}
