<?php

namespace Balongo\AdminBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensaje
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Mensaje
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
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="cuerpo", type="blob")
     */
    private $cuerpo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var Balongo\AdminBundle\Entity\Comunidad
     *
     * @ORM\ManyToOne(targetEntity="Balongo\AdminBundle\Entity\Comunidad")
     * @ORM\JoinColumn(name="comunidad_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $comunidad;
    
    /**
     * @ORM\OneToMany(targetEntity="Balongo\AdminBundle\Entity\Archivo", mappedBy="mensaje")
     */
    private $archivos;
    
    

    public function __construct() {
        $this->archivos = new ArrayCollection();
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
     * Set titulo
     *
     * @param string $titulo
     * @return Mensaje
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set cuerpo
     *
     * @param string $cuerpo
     * @return Mensaje
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
    	 if ($this->cuerpo != '')
		     return stream_get_contents($this->cuerpo);
        return $this->cuerpo;
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

    /**
     * Set comunidad
     *
     * @param \Balongo\AdminBundle\Entity\Comunidad $comunidad
     * @return Mensaje
     */
    public function setComunidad($comunidad)
    {
        $this->comunidad = $comunidad;

        return $this;
    }

    /**
     * Get comunidad
     *
     * @return \Balongo\AdminBundle\Entity\Comunidad 
     */
    public function getComunidad()
    {
        return $this->comunidad;
    }

    /**
     * Set archivos
     *
     * @param ArrayCollection $archivos
     * @return Mensaje
     */
    public function setArchivos($archivos)
    {
        $this->archivos = $archivos;

        return $this;
    }

    /**
     * Get archivos
     *
     * @return ArrayCollection
     */
    public function getArchivos()
    {
        return $this->archivos;
    }
}
