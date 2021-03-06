<?php

namespace Balongo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comunidad
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Comunidad
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
     * @ORM\Column(name="nombre", type="string", length=127)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Balongo\AdminBundle\Entity\Usuario", mappedBy="comunidades", cascade="remove")
     */
    private $usuarios;
    
    
    
    
    public function __construct()
    {
		$this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Comunidad
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
     * Set direccion
     *
     * @param string $direccion
     * @return Comunidad
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
     * Set usuarios
     *
     * @param array $usuarios
     * @return Comunidad
     */
    public function setUsuarios($usuarios)
    {
        $this->usuarios = $usuarios;

        return $this;
    }

    /**
     * Get usuarios
     *
     * @return array 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
    
    
    public function __toString()
    {
    	return $this->getNombre();
    }
}
