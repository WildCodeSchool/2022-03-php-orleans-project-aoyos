<?php

namespace App\Controller;

use App\Form\ArtistType;
use App\Form\ArtistProfileType;
use App\Repository\ArtistRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/dashboard/dj', name: 'dashboard_dj_')]
class DjProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile', methods: ['GET', 'POST'])]
    public function index(ArtistRepository $artistRepository, Request $request): Response
    {
        // $emailArtist = $this->getUser()->getEmail();
        $emailArtist = 'yost.leora@gmail.com';
        $artist = $artistRepository->findOneBy(['email' => $emailArtist]);

        $formBase = $this->createForm(ArtistType::class, $artist);
        $formComplement = $this->createForm(ArtistProfileType::class, $artist);
        $formBase->handleRequest($request);
        $formComplement->handleRequest($request);

        if (
            ($formBase->isSubmitted() && $formBase->isValid())
            && ($formComplement->isSubmitted() && $formComplement->isValid())
            ) {
            $artistRepository->add($artist, true);

            return $this->redirectToRoute('dashboard_dj_profile', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('dashboard-dj/profile/edit.html.twig', [
            'artist' => $artist,
            'formBase' => $formBase,
            'formComplement' => $formComplement,
        ]);
    }
}
