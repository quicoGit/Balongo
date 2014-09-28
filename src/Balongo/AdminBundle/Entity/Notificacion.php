<?php

namespace Balongo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notificacion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Notificacion
{
    /**
     * @var Balongo\AdminBundle\Entity\Usuario
     * 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Balongo\AdminBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $usuario;

    /**
     * @var Balongo\AdminBundle\Entity\Mensaje
     * 
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Balongo\AdminBundle\Entity\Mensaje")
     * @ORM\JoinColumn(name="mensaje_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $mensaje;
    
	/**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Notificacion
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set mensaje
     *
     * @param string $mensaje
     * @return Notificacion
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return string 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Notificacion
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }
    
    
    public function addCount()
    {
        $this->count = $this->count + 1;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }
}
