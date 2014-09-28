<?php

namespace Balongo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comentario
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Comentario
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
     * @ORM\Column(name="cuerpo", type="blob")
     */
    private $cuerpo;

    /**
     * @var Balongo\AdminBundle\Entity\Mensaje
     * 
     * @ORM\ManyToOne(targetEntity="Balongo\AdminBundle\Entity\Mensaje")
     * @ORM\JoinColumn(name="mensaje_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $mensaje;

    /**
     * @var Balongo\AdminBundle\Entity\Usuario
     * 
     * @ORM\ManyToOne(targetEntity="Balongo\AdminBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $usuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;


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
     * Set cuerpo
     *
     * @param string $cuerpo
     * @return Comentario
     */
    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    /**
     * Get cuerpo
     *
     * @return string 
     */
    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    /**
     * Set mensaje
     *
     * @param Balongo\AdminBundle\Entity\Mensaje $mensaje
     * @return Comentario
     */
    public function setMensaje(\Balongo\AdminBundle\Entity\Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return Balongo\AdminBundle\Entity\Mensaje 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set usuario
     *
     * @param Balongo\AdminBundle\Entity\Usuario $usuario
     * @return Comentario
     */
    public function setUsuario(\Balongo\AdminBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return Balongo\AdminBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set fecha
     *
     * @ORM\PrePersist
     */
    public function setFecha()
    {
        $this->fecha = new \DateTime();
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
