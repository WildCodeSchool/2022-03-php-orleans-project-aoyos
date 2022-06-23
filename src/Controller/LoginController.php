<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();
         return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
         ]);
    }

    #[Route('/redirection', name: 'redirect')]
    public function redirectAfterLogin(): response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        } elseif (in_array('ROLE_USER', $this->getUser()->getRoles(), true)) {
            return $this->redirectToRoute('dashboard_dj_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }
}
