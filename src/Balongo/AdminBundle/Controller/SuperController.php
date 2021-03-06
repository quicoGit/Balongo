<?php

namespace Balongo\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Balongo\AdminBundle\Entity\Usuario;
use Balongo\AdminBundle\Form\UsuarioAdminType;

class SuperController extends Controller
{
	 public function indexAction()
	 {
	 	$em = $this->getDoctrine()->getManager();
	 	
	 	return $this->render(
	 		'AdminBundle:Super:index.html.twig',
	 		array(
	 			'count_users' 			=> $em->createQuery('SELECT count(u.id) as cou FROM AdminBundle:Usuario u')->getSingleResult(),
	 			'count_admins' 		=> $em->createQuery('SELECT count(u.id) as cou FROM AdminBundle:Usuario u WHERE u.rol = 1')->getSingleResult(),
	 			'count_comunidades' 	=> $em->createQuery('SELECT count(c.id) as cou FROM AdminBundle:Comunidad c')->getSingleResult(),
	 			'count_mensajes' 		=> $em->createQuery('SELECT count(m.id) as cou FROM AdminBundle:Mensaje m')->getSingleResult(),
	 			'count_archivos' 		=> $em->createQuery('SELECT count(a.id) as cou FROM AdminBundle:Archivo a')->getSingleResult(),
	 			'count_kbs' 			=> $em->createQuery('SELECT sum(a.kb) as su FROM AdminBundle:Archivo a')->getSingleResult()
	 		)
	 	);
	 }
	 
	 
	 /**
     * CREAR USUARIOS ADMIN. (FORMULARIO PARA CREAR USUARIOS ADMIN)
     */
    public function cUsuariosAction(Request $request)
    {
    	$usuario = new Usuario();
    	$form = $this->createForm(new UsuarioAdminType(), $usuario);
    	
    	$form->handleRequest($request);
    	
    	if ( $form->isValid() ) {
    		$usuario->setRol(1);
			$usuario->setPassword( md5(time()) );
			$usuario->setSalt( md5(time()) );
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($usuario);
    		$em->flush();
    		
    		$message = \Swift_Message::newInstance()
			->setSubject( 'BALONGO: Nueva cuenta administrador.' )
			->setFrom( 'webmaster@balongo.eu' )
			->setTo( $usuario->getEmail() )
			->setBody(
				$this->renderView(
					'AdminBundle:Emailing:nuevo_admin.txt.twig',
					array(
						'usuario' => $usuario
					)
				)
			);
			$this->get('mailer')->send($message);    		
    		$this->get('session')->getFlashBag()->add('success', true);
    		
    		return $this->redirect($this->generateUrl('super_r_usuario'));
    	}
    	
    	return $this->render(
    		'AdminBundle:Super:C_Usuario.html.twig',
    		array(
    			'form' => $form->createView()
    		)
    	);
    }
    
    
    /**
     * LEER USUARIOS ADMIN. (TABLA QUE PERMITE LISTAR USUARIOS ADMIN, FILTRO Y DELETE)
     */
    public function rUsuariosAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$q = $em->createQuery('SELECT u FROM AdminBundle:Usuario u WHERE u.rol = 1 ORDER BY u.apellidos ASC');
    	$usuarios = $q->getResult();
    	
    	return $this->render('AdminBundle:Super:R_Usuario.html.twig',
    		array(
    			'usuarios' => $usuarios
    		)
    	);
    }
    
    
    /**
     * ELIMINAR USUARIOS. (AJAX, RECIBE UN ARRAY CON LOS IDs DE LOS USUARIOS ADMIN A ELIMINAR)
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
}
