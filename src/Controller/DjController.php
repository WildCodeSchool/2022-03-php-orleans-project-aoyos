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
        MusicalStyle $musicalStyle,
        RequestStack $stack
    ): Response {
        $session = $stack->getSession();
        $artist = $session->get('artistInfos') ?? new Artist();

        if (!$session->has('step')) {
            $form = $this->createForm(ArtistType::class, $artist);
        } else {
            $form = $this->createForm(ArtistProfileType::class, $artist);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$session->has('step')) {
                $artistInfos = $form->getData();
                $session->set('artistInfos', $artistInfos);
                $session->set('step', 1);
            } else {
                $artistRepository->add($artist, true);
            }
            return $this->redirectToRoute('app_registration', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'dj/registration/index.html.twig',
            [ 'form' => $form,
             'artist' => $artist,
             'musical_style' => $musicalStyle,
            ]
        );
    }
}
