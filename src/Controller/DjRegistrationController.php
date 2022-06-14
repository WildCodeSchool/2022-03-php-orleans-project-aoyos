<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inscription', name: 'app_')]
class DjRegistrationController extends AbstractController
{
    #[Route('/', name: 'app_registration')]
    public function index(): Response
    {
        return $this->renderForm('dj/registration/index.html.twig');
    }
}
