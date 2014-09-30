<?php

namespace Balongo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Archivo
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Archivo
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var float
     *
     * @ORM\Column(name="kb", type="decimal", nullable=false)
     */
    private $kb;
    
    
    /**
     * @var Balongo\AdminBundle\Entity\Mensaje
     * 
     * @ORM\ManyToOne(targetEntity="Balongo\AdminBundle\Entity\Mensaje", inversedBy="archivos")
     * @ORM\JoinColumn(name="mensaje_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $mensaje;
    
    /**
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     * @Assert\File(
     * 		maxSize="100M",
     * 		mimeTypes={"image/png", "image/jpg", "image/jpeg", "image/gif"},
     * 		maxSizeMessage="Este archivo es demasiado grande ({{ size }} {{ suffix }}). El tamaño máximo permitido es {{ limit }} {{ suffix }}.",
     * 		mimeTypesMessage="Este tipo de archivo es inválido ({{ type }}). Los tipos de archivos permitidos son: {{ types }}.",
     * 		uploadErrorMessage="Este archivo no puede subirse al servidor."
     * )
     */
    private $file;
    
    
    private $temp;
    
    
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
     * Set name
     *
     * @param string $name
     * @return Documento
     */
    public function setName($name)
    {
        $this->name = preg_replace('/^[A-Za-z0-9áéíóúàèìòùÁÉÍÓÚÀÈÌÒÙñÑüÜïÏçÇ_]*/', '', $name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set path
     *
     * @param string $path
     * @return Documento
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set kb
     *
     * @param float $kb
     * @return Documento
     */
    public function setKb($kb)
    {
        $this->kb = $kb;

        return $this;
    }

    /**
     * Get kb
     *
     * @return float 
     */
    public function getKb()
    {
        return $this->kb;
    }
    
    /**
     * Set mensaje
     *
     * @param \Balongo\AdminBundle\Entity\Mensaje $mensaje
     * @return Archivo
     */
    public function setMensaje(\Balongo\AdminBundle\Entity\Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return \Balongo\AdminBundle\Entity\Mensaje
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    protected function getUploadRootDir()
    {
        return realpath( __DIR__.'/../../../../web/'.$this->getUploadDir() );
    }

    protected function getUploadDir()
    {
        return 'uploads/files';
    }
    
    
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            $this->name = $this->getFile()->getClientOriginalName();
		  		$this->kb = ($this->getFile()->getClientSize())/1024;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
    	  if ( $file = $this->getUploadRootDir().'/'.$this->path ) {
            unlink($file);
        }
    }
}
