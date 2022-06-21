<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/redirection', name: 'redirect_')]
class RedirectionController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            return $this->redirectToRoute('adminindex', [], Response::HTTP_SEE_OTHER);
        } elseif (in_array('ROLE_USER', $this->getUser()->getRoles(), true)) {
            return $this->redirectToRoute('registered_dj_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
    }
}
