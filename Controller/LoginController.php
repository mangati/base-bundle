<?php

namespace Mangati\BaseBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * LoginController.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class LoginController extends Controller
{
    /**
     * @Route("/login")
     * 
     * @param Request $request
     *
     * @return array
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render($this->getLoginTemplate(), [
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    protected function getLoginTemplate()
    {
        return 'MangatiBaseBundle:Login:login.html.twig';
    }
}
