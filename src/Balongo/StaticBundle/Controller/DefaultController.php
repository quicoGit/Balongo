<?php

namespace Balongo\StaticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
	public function indexAction()
	{
		return $this->render('StaticBundle:Default:index.html.twig');
	}
	
	public function loginAction()
	{
		$peticion = $this->getRequest();
		$sesion = $peticion->getSession();
		
		$error = $peticion->attributes->get(
			SecurityContext::AUTHENTICATION_ERROR,
			$sesion->get(SecurityContext::AUTHENTICATION_ERROR)
		);		
		
		return $this->render('StaticBundle:Default:login.html.twig', 
			array(
				'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
				'error' => $error
			)
		);
	}
	
	public function conocenosAction()
	{
		return $this->render('StaticBundle:Default:conocenos.html.twig');
	}
	
	public function contactoAction()
	{
		$x = rand(0, 10);
		$y = rand(0, 10);
		return $this->render('StaticBundle:Default:contacto.html.twig', array( 'x'=>$x, 'y'=>$y ) );
	}
	
	public function legalAction()
	{
		return $this->render('StaticBundle:Default:legal.html.twig');
	}
    
    public function contactoFormAction(Request $request)
    {
		if ( $request->isXmlHttpRequest() ) {
			$hidden = $request->request->get('_hidden');
			$secret = $request->request->get('_secret');
			if ( $hidden == $secret ) {
				$message = \Swift_Message::newInstance()
					->setSubject( 'Mensaje desde BALONGO.EU' )
					->setFrom( $request->request->get('_email') )
					->setTo( 'fincas@balongo.eu' )
					->setBody(
						$this->renderView(
							'StaticBundle:Default:mensaje.txt.twig',
							array(
								'nombre' => $request->request->get('_nombre'),
								'telefono' => $request->request->get('_tel'),
								'soy' => $request->request->get('_soy'),
								'mensaje' => $request->request->get('_mensaje')
							)
						)
					);
				
				if (  $this->get('mailer')->send($message) ) {
					return new JsonResponse(array('data' => 'success'));
				} else {
					return new JsonResponse(array('data' => 'error'));
				}
			} else {
				return new JsonResponse(array('data' => 'error'));
			}
		} else {
			return new JsonResponse(array('data' => 'error'));
		}
	}
	
	
	public function activarFormAction($id, $salt, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		
		if ( $user = $em->find('AdminBundle:Usuario', $id) ) {
		
			if ( !$user->getActivo() && $user->getSalt() == $salt ) {
				// INACTIVO Y SALT CORRECTO MOSTRAR FORMULARIO DE CONTRASEÑA
				$form = $this->createFormBuilder($user)
					->add('password', 'repeated', array(
		         	'type' => 'password',
		         	'invalid_message' => 'Las contraseñas deben coincidir.',
		         	'options' => array('attr'=>array('class'=>'form-control')),
		         	'required' => true,
						'first_options'  => array('label' => 'Contraseña'),
						'second_options' => array('label' => 'Repite Contraseña')
		         ))
		         ->getForm();
		         
		      $form->handleRequest($request);
			
				if ( $form->isValid() ) {
					$encoder = $this->get('security.encoder_factory')->getEncoder($user);
					$password = $form->get('password')->getData();
					$passwordCodificado = $encoder->encodePassword(
							$password,
							$user->getSalt()
					);
					$user->setPassword($passwordCodificado);
					$user->setActivo( true );
					
					$em->persist($user);
    				$em->flush();
					
					$this->get('session')->getFlashBag()->add('success', true);
    				return $this->redirect($this->generateUrl('static_login'));
				}
			
				return $this->render('StaticBundle:Default:activarCuenta.html.twig',
					array(
						'form' => $form->createView(),
						'id' => $id,
						'salt' => $salt
					)
				);			
			
			}
		}
		// NO CUMPLE REQUISITOS. REDIRECT INICIO.
		return $this->redirect($this->generateUrl('static_index'));
	}
}
