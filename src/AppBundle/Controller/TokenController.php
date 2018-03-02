<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends FOSRestController
{
    public function cpostAction(Request $request, ParamFetcher $paramFetcher)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneBy(['email' => $username]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $isValid = $this->get('security.password_encoder')->isPasswordValid($user, $password);

        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600
            ]);

        $view = $this->view(['token' => $token])->setStatusCode(Response::HTTP_CREATED);
        return $this->handleView($view);
    }
}
