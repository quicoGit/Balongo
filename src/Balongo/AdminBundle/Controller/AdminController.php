<?php

namespace Balongo\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Balongo\AdminBundle\Entity\Usuario;
use Balongo\AdminBundle\Entity\Comunidad;
use Balongo\AdminBundle\Entity\Mensaje;
use Balongo\AdminBundle\Entity\Archivo;
use Balongo\AdminBundle\Form\UsuarioUserType;
use Balongo\AdminBundle\Form\ComunidadType;

class AdminController extends Controller
{
	 
	 //----------------------------------------------------------
	 //----------------------------------------------------------
	 //				CRUD COMUNIDADES
	 //----------------------------------------------------------
	 //----------------------------------------------------------
	 
	 /**
     * CREAR COMUNIDAD. (FORMULARIO PARA CREAR COMUNIDAD)
     */
    public function cComunidadesAction(Request $request)
    {
    	$comunidad = new Comunidad();
    	$form = $this->createForm(new ComunidadType(), $comunidad);
    	
    	$form->handleRequest($request);
    	
    	if ( $form->isValid() ) {    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($comunidad);
    		$em->flush();
    		  		
    		$this->get('session')->getFlashBag()->add('success', true);
    		
    		return $this->redirect($this->generateUrl('admin_r_comunidad'));
    	}
    	
    	return $this->render(
    		'AdminBundle:Admin:C_Comunidad.html.twig',
    		array(
    			'form' => $form->createView()
    		)
    	);
    }
    
    
    /**
     * LEER COMUNIDADES. (TABLA QUE PERMITE LISTAR COMUNIDADES, FILTRO, UPDATE Y DELETE)
     */
    public function rComunidadesAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$q = $em->createQuery('SELECT c FROM AdminBundle:Comunidad c ORDER BY c.nombre ASC');
    	$comunidades = $q->getResult();
    	
    	return $this->render('AdminBundle:Admin:R_Comunidad.html.twig',
    		array(
    			'comunidades' => $comunidades
    		)
    	);
    }
    
    
    /**
     * UPDATE COMUNIDADES. (FORMULARIO PERMITE ACTUALIZAR COMUNIDAD)
     */
    public function uComunidadesAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$comunidad = $em->find('AdminBundle:Comunidad', $id);
    	$form = $this->createForm(new ComunidadType(), $comunidad);
    	
    	$form->handleRequest($request);
    	
    	if ( $form->isValid() ) {
    		$em->persist($comunidad);
    		$em->flush();
    		  		
    		$this->get('session')->getFlashBag()->add('success', true);
    		
    		return $this->redirect($this->generateUrl('admin_r_comunidad'));
    	}
    	
    	return $this->render(
    		'AdminBundle:Admin:U_Comunidad.html.twig',
    		array(
    			'form' => $form->createView(),
    			'id' => $id
    		)
    	);
    }
    
    
    /**
     * ELIMINAR COMUNIDADES. (AJAX, RECIBE UN ARRAY CON LOS IDs DE LAS COMUNIDADES A ELIMINAR)
     */
    public function dComunidadesAction(Request $request)
    {
    	if ( $request->isXmlHttpRequest() ) {
    		$comunidades = $request->request->get('comunidades');
			if ( !empty($comunidades) ) {
				$em = $this->getDoctrine()->getManager();
				foreach($comunidades as $c) {
					$comunidad = $em->find('AdminBundle:Comunidad', $c);
					$em->remove($comunidad);
					$em->flush();
				}
				return new JsonResponse(array('data' => 'success'));			
			}
		}
		return new JsonResponse(array('data' => 'error'));
    }
	 
	 
	 //----------------------------------------------------------
	 //----------------------------------------------------------
	 //				CRUD USUARIOS NORMALES
	 //----------------------------------------------------------
	 //----------------------------------------------------------
	 
	 
	 /**
     * CREAR USUARIOS. (FORMULARIO PARA CREAR USUARIOS)
     */
    public function cUsuariosAction(Request $request)
    {
    	$usuario = new Usuario();
    	$form = $this->createForm(new UsuarioUserType(), $usuario);
    	
    	$form->handleRequest($request);
    	
    	if ( $form->isValid() ) {
    		$usuario->setRol(2);
			$usuario->setPassword( md5(time()) );
			$usuario->setSalt( md5(time()) );
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($usuario);
    		$em->flush();
    		
    		$message = \Swift_Message::newInstance()
			->setSubject( 'BALONGO: Nueva cuenta cliente.' )
			->setFrom( 'webmaster@balongo.eu' )
			->setTo( $usuario->getEmail() )
			->setBody(
				$this->renderView(
					'AdminBundle:Emailing:nuevo_user.txt.twig',
					array(
						'usuario' => $usuario
					)
				)
			);
			$this->get('mailer')->send($message);    		
    		$this->get('session')->getFlashBag()->add('success', true);
    		
    		return $this->redirect($this->generateUrl('admin_r_usuario'));
    	}
    	
    	return $this->render(
    		'AdminBundle:Admin:C_Usuario.html.twig',
    		array(
    			'form' => $form->createView()
    		)
    	);
    }
    
    
    /**
     * LEER USUARIOS. (TABLA QUE PERMITE LISTAR USUARIOS, FILTRO Y DELETE)
     */
    public function rUsuariosAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$q = $em->createQuery('SELECT u FROM AdminBundle:Usuario u WHERE u.rol = 2 ORDER BY u.apellidos ASC');
    	$usuarios = $q->getResult();
    	
    	return $this->render('AdminBundle:Admin:R_Usuario.html.twig',
    		array(
    			'usuarios' => $usuarios
    		)
    	);
    }
    
    
    
    /**
     * UPDATE USUARIOS. (FORMULARIO PERMITE ACTUALIZAR USUARIO)
     */
    public function uUsuariosAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$usuario = $em->find('AdminBundle:Usuario', $id);
    	$form = $this->createForm(new UsuarioUserType(), $usuario);
    	
    	$form->handleRequest($request);
    	
    	if ( $form->isValid() ) {
    		$em->persist($usuario);
    		$em->flush();
    		
    		$this->get('session')->getFlashBag()->add('success', true);
    		return $this->redirect($this->generateUrl('admin_r_usuario'));
    	}
    	
    	return $this->render(
    		'AdminBundle:Admin:U_Usuario.html.twig',
    		array(
    			'form' => $form->createView(),
    			'id' => $id
    		)
    	);
    }
    
    
    /**
     * ELIMINAR USUARIOS. (AJAX, RECIBE UN ARRAY CON LOS IDs DE LOS USUARIOS A ELIMINAR)
     */
    public function dUsuariosAction(Request $request)
    {
    	if ( $request->isXmlHttpRequest() ) {
    		$usuarios = $request->request->get('usuarios');
			if ( !empty($usuarios) ) {
				$em = $this->getDoctrine()->getManager();
				foreach($usuarios as $u) {
					$user = $em->find('AdminBundle:Usuario', $u);
					$em->remove($user);
					$em->flush();
				}
				return new JsonResponse(array('data' => 'success'));			
			}
		}
		return new JsonResponse(array('data' => 'error'));
    }
    
    
    
    //----------------------------------------------------------
	 //----------------------------------------------------------
	 //				CRUD MENSAJES
	 //----------------------------------------------------------
	 //----------------------------------------------------------
	 
	 /**
	  * CREAR MENSAJE
	  */
	 public function cMensajesAction(Request $request)
	 {
	 	$mensaje = new Mensaje();
    	$form = $this->createFormBuilder()
    			->add('titulo', 'text', array(
            	'attr'=> array('class'=>'form-control')
            ))
            ->add('cuerpo', 'textarea', array(
            	'label' => 'Cuerpo (El texto no se visualizará con formato)',
            	'attr'=> array('class'=>'form-control')
            ))
            ->add('comunidad', 'entity', array(
            	'class' => 'AdminBundle:Comunidad',
            	'multiple' => false,
            	'attr' => array('class' => 'form-control')
            ))
            ->add('archivos', 'file', array(
            	'multiple' => true,
            	'attr' => array('class'=>'form-control'),
            	'required' => false
            ))
            ->getForm();
    	
    	$form->handleRequest($request);
    	
    	if ( $form->isValid() ) {
    		$em = $this->getDoctrine()->getManager();
    		
    		$data = $form->getData();
    		$mensaje->setTitulo( $data['titulo'] );
    		$mensaje->setCuerpo( $data['cuerpo'] );
    		$mensaje->setComunidad( $data['comunidad'] );
    		$mensaje->setFecha( new \DateTime() );
    		$archivos = array();
    		if ( !empty( $data['archivos'][0] ) )
    		{
		 		foreach ( $data['archivos'] as $a )
		 		{
		 			$archivo = new Archivo();
		 			$archivo->setFile( $a );
		 			$archivo->setMensaje( $mensaje );
		 			$em->persist($archivo);
		 			array_push($archivos, $archivo);
		 		}
		 	}
    		$mensaje->setArchivos($archivos);
    		
    		$em->persist($mensaje);
    		$em->flush();
    		
    		$usuarios = $mensaje->getComunidad()->getUsuarios();
    		foreach ( $usuarios as $u )
    		{
    			if ( $u->getEmailing() )
    			{
    				$message = \Swift_Message::newInstance()
					->setSubject( 'BALONGO: Nuevo mensaje.' )
					->setFrom( 'webmaster@balongo.eu' )
					->setTo( $u->getEmail() )
					->setBody(
						$this->renderView(
							'AdminBundle:Emailing:nuevo_mensaje.txt.twig',
							array(
								'mensaje' => $mensaje,
								'usuario' => $u
							)
						)
					);
    			}
    		}
    		
			$this->get('mailer')->send($message);    		
    		$this->get('session')->getFlashBag()->add('success', true);
    		
    		return $this->redirect($this->generateUrl('admin_index'));
    	}
    	
    	return $this->render(
    		'AdminBundle:Admin:C_Mensaje.html.twig',
    		array(
    			'form' => $form->createView()
    		)
    	);
	 }
	 
	 /**
	  * LEER MENSAJES. PÁGINA INICIO ADMINISTRADORES.
	  */
	 public function rMensajesAction()
	 {
	 	$em = $this->getDoctrine()->getManager();
    	$q = $em->createQuery('SELECT m FROM AdminBundle:Mensaje m ORDER BY m.fecha DESC');
    	$mensajes = $q->getResult();
    	
	 	return $this->render('AdminBundle:Admin:R_Mensaje.html.twig',
	 		array(
	 			'mensajes' => $mensajes
	 		)
	 	);
	 }
	 
	 /**
	  * ELIMINAR MENSAJES
	  */
	 public function dMensajesAction(Request $request)
	 {
	 	if ( $request->isXmlHttpRequest() ) {
    		$mensajes = $request->request->get('mensajes');
			if ( !empty($mensajes) ) {
				$em = $this->getDoctrine()->getManager();
				foreach($mensajes as $m) {
					$men = $em->find('AdminBundle:Mensaje', $m);
					$arc= $men->getArchivos();
					foreach ($arc as $a)
					{
						$a->removeUpload();
					}
					$em->remove($men);
					$em->flush();
				}
				return new JsonResponse(array('data' => 'success'));			
			}
		}
		return new JsonResponse(array('data' => 'error'));
	 }
}
