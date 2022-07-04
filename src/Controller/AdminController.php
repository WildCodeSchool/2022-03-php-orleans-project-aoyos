<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    public const MAX_ELEMENTS = 3;

    #[Route('/', name: 'index')]
    public function index(ArtistRepository $artistRepository, ReservationRepository $reservationRepo): Response
    {
        return $this->render('admin/index.html.twig', [
            'artists' => $artistRepository->findBy([], ['id' => 'DESC'], self::MAX_ELEMENTS),
            'reservations' => $reservationRepo->findBy([], ['id' => 'DESC'], self::MAX_ELEMENTS),
        ]);
    }

    #[Route('/dj/{id}', name: 'dj_show', methods: ['GET'])]
    public function show(Artist $artist): Response
    {
        return $this->render('admin/dj/show.html.twig', [
            'artist' => $artist,
        ]);
    }
}
