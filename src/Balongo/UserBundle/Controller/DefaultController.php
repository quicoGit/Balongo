<?php

namespace Balongo\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Balongo\AdminBundle\Entity\Usuario;
use Balongo\AdminBundle\Entity\Comunidad;
use Balongo\AdminBundle\Entity\Mensaje;
use Balongo\AdminBundle\Entity\Archivo;

class DefaultController extends Controller
{
    public function rMensajeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mensaje = $em->find('AdminBundle:Mensaje', $id);
        
        if ( $this->getUser()->getRol() == 1 || in_array( $mensaje->getComunidad(), $this->getUser()->getComunidades() ) )
        {
        		return $this->render(
        			'UserBundle:Default:Mensaje.html.twig',
			 		array(
			 			'mensaje' => $mensaje
			 		)
    			);
        }
        else
        {
        		return $this->redirect($this->generateUrl('user_index'));
        }
    }
    
    
    
    
    public function descargaAdjuntoAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$archivo = $em->find('AdminBundle:Archivo', $id);
    	
    	// ¿A qué comunidad pertenece este archivo?
    	$comunidad = $archivo->getMensaje()->getComunidad()->getId();
    	
    	// ¿Posee el usuario actual permiso sobre archivos de esa comunidad?
    	$user = $this->getUser();
    	$comunidades = $user->getComunidades();    	
    	
    	if ( $user->getRol() == 1 || in_array( $comunidad, $comunidades ) ) {
    		$path = realpath( __DIR__.'/../../../../web/uploads/files').'/'.$archivo->getPath();
			
			// Vamos a mostrar un PDF
			$response = new Response();
			// Set headers
			$response->headers->set('Cache-Control', 'private');
			$response->headers->set('Content-type', 'application/unknown');
			$response->headers->set('Content-Disposition', 'attachment; filename="'.$archivo->getName().'";');
			$response->headers->set('Content-length', filesize($path));
					 
			// Send headers before outputting anything
			$response->sendHeaders();
					 
			$response->setContent(readfile($path));
					 
			return $response;
    	}
    	
    	// No posee permiso sobre ese archivo
    	// No se notifica
    	// Se redirecciona a la página de inicio
    	$rol = $user->getRol();
    	switch($rol){
    		case 1: return $this->redirect($this->generateUrl('admin_index')); 	break;
    		case 2: return $this->redirect($this->generateUrl('user_index'));		break;
    	}
    }
    
    
    
    
    
    public function uPerfilAction()
    {
    	return $this->render(
     		'UserBundle:Default:Perfil.html.twig',
		 	array(
				'usuario' => $this->getUser()
	 		)
		);
    }
    
    public function uPerfilDatosAction(Request $request)
    {
    	if ( $request->isXmlHttpRequest() ) {
    		if ( $_POST ) {
    			extract( $_POST ); //_nombre, _apellidos, _emailing
    			$user = $this->getUser();
    			$user->setNombre($_nombre);
    			$user->setApellidos($_apellidos);
    			$emailing = ( isset($_emailing) )? true: false;
    			$user->setEmailing($emailing);
    			
    			$em = $this->getDoctrine()->getManager();
    			$em->persist($user);
    			$em->flush();
    			
    			return new JsonResponse( array(
    				'status' => 'success',
    				'mensaje' => 'Tus datos personales han sido modificados con éxito.'
    			));
    		}
		}
		return new JsonResponse(array(
			'status' => 'error',
			'mensaje' => 'Ha ocurrido un error al intentar modificar tus datos.'
		));
    }
    
    public function uPerfilEmailAction(Request $request)
    {
    	if ( $request->isXmlHttpRequest() ) {
    		if ( $_POST ) {
    			extract( $_POST ); //_pass, _email, _email_2
    			$user = $this->getUser();
    			$encoder = $this->get('security.encoder_factory')->getEncoder($user);
				$passCod = $encoder->encodePassword(
						$_pass,
						$user->getSalt()
				);
				
				if ( $_email != $_email_2 ) {
					return new JsonResponse(array(
						'status' => 'error',
						'mensaje' => 'Las dos direcciones de correo electrónico no coinciden.'
					));
				}
				
				if ( $passCod == $user->getPassword() ) {
					$em = $this->getDoctrine()->getManager();
					//ASEGURARSE DE QUE EL EMAIL NO EXISTE EN LA BBDD MANUALMENTE
					$q = $em->createQuery("SELECT u FROM AdminBundle:Usuario u WHERE u.email = '".$_email."'");
					if ( $q->getResult() ) {
						return new JsonResponse(array(
							'status' => 'error',
							'mensaje' => 'El correo electrónico introducido ya existe en la base de datos.'
						));
					}
					$user->setEmail( $_email );
					$user->setActivo( false );
    		
			 		$em->persist($user);
			 		$em->flush();
			 		
			 		$message = \Swift_Message::newInstance()
					->setSubject( 'BALONGO: Activa tu nuevo email.' )
					->setFrom( 'webmaster@balongo.eu' )
					->setTo( $user->getEmail() )
					->setBody(
						$this->renderView(
							'AdminBundle:Emailing:nuevo_email.txt.twig',
							array(
								'usuario' => $user
							)
						)
					);
					$this->get('mailer')->send($message);
					
					return new JsonResponse(array(
						'status' => 'success',
						'mensaje' => 'Para activar tu nuevo email, chequea tu bandeja de correo y sigue las instrucciones.',
						'desconecta' => true
					));
					
				} else {
					return new JsonResponse(array(
						'status' => 'error',
						'mensaje' => 'La contraseña que has introducido es incorrecta.'
					));
				}    			
    		}
		}
		return new JsonResponse(array(
			'status' => 'error',
			'mensaje' => 'Ha ocurrido un error al intentar modificar tu email.'
		));
    }
    
    
    
    public function uPerfilPassAction(Request $request)
    {
    	if ( $request->isXmlHttpRequest() ) {
    		if ( $_POST ) {
    			extract( $_POST ); // _pass_antiguo, _pass_nuevo_1, _pass_nuevo_2
    			$user = $this->getUser();
    			$encoder = $this->get('security.encoder_factory')->getEncoder($user);
				$passCod = $encoder->encodePassword(
						$_pass_antiguo,
						$user->getSalt()
				);
				
				if ( $passCod != $user->getPassword() )
				return new JsonResponse(array(
					'status' => 'error',
					'mensaje' => 'La contraseña actual introducida no es correcta.'
				));
				
				if ( $_pass_nuevo_1 != $_pass_nuevo_2 )
				return new JsonResponse(array(
					'status' => 'error',
					'mensaje' => 'La contraseñas no coinciden.'
				));
				
				$passNew = $encoder->encodePassword(
					$_pass_nuevo_1,
					$user->getSalt()
				);
				$em = $this->getDoctrine()->getManager();
				$user->setPassword( $passNew );
				$em->persist( $user );
				$em->flush();
				
				return new JsonResponse(array(
					'status' => 'success',
					'mensaje' => 'Tu contraseña ha sido modificada con éxito.'
				));
    		}
    	}
    }
    
}
