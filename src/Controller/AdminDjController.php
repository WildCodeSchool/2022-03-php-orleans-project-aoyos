<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dj', name: 'admin_dj_')]
class AdminDjController extends AbstractController
{
    #[Route('/profil', name: 'profile', methods: ['GET'])]
    public function index(ArtistRepository $artistRepository): Response
    {
        // $emailArtist = $this->getUser()->getEmail();
        $emailArtist = 'wbarrows@yahoo.com';
        return $this->render('admin-dj/profile/index.html.twig', [
            'artist' => $artistRepository->findOneBy(['email' => $emailArtist]),
        ]);
    }
}
