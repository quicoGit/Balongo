<?php

// src/Balongo/AdminBundle/Listener/LoginListener.php

namespace Balongo\AdminBundle\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginListener
{
	private $router, $contexto, $user = null;
	
	public function __construct(SecurityContext $context, Router $router) {
		$this->contexto = $context;
		$this->router = $router;
	}
	
	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
		$token = $event->getAuthenticationToken();
		$this->user = $token->getUser();
	}
	
	public function onKernelResponse(FilterResponseEvent $event) {
		if ($this->user != null) {
			if($this->contexto->isGranted('ROLE_USER')) {
				$portada = $this->router->generate('user_index', array('id'=>'default'));
			} else if($this->contexto->isGranted('ROLE_ADMIN')) {
				$portada = $this->router->generate('admin_index');
			} else if ($this->contexto->isGranted('ROLE_SUPER')) {
				$portada = $this->router->generate('super_index');
			} else {
				$portada = $this->router->generate('static_index');
			}
			$event->setResponse(new RedirectResponse($portada));
			$event->stopPropagation();
		}
	}
}
