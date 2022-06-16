<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\MusicalStyle;
use App\Form\ArtistProfileType;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dj', name: 'app_')]
class DjController extends AbstractController
{
    #[Route('/', name: 'dj')]
    public function index(): Response
    {
        return $this->render('dj/index.html.twig');
    }

    #[Route('/inscription', name: 'registration')]
    public function register(
        HttpFoundationRequest $request,
        ArtistRepository $artistRepository,
        RequestStack $stack
    ): Response {
        $session = $stack->getSession();
        $artist = $session->get('artistInfos') ?? new Artist();

        if (!$session->get('step')) {
            $form = $this->createForm(ArtistType::class, $artist);
        } else {
            $form = $this->createForm(ArtistProfileType::class, $artist);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$session->get('step')) {
                $session->set('artistInfos', $artist);
                $session->set('step', 2);
            } else {
                $session->remove('artistInfos');
                $session->remove('step');
                $artistRepository->add($artist, true);

                return $this->redirectToRoute('app_dj', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_registration', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'dj/registration/index.html.twig',
            [ 'form' => $form,
             'artist' => $artist,
            ]
        );
    }

    #[Route('/return', name: 'return')]
    public function return(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        if ($session->has('step')) {
            $session->remove('step');
        }
        return $this->redirectToRoute('app_registration', [], Response::HTTP_SEE_OTHER);
    }
}
