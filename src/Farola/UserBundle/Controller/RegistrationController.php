<?php

namespace Farola\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
   public function confirmAction($token)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {

            $logger = $this->container->get('logger');
            $logger->error('Token not found : '.$token);
            return new RedirectResponse($this->container->get('router')->generate('farola_home'));
        }
        else{
            // Token found. Letting the FOSUserBundle's action handle the confirmation 
             return parent::confirmAction($token);
        }
    }
}
