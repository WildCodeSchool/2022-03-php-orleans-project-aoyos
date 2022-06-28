<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ArtistRepository;
use App\Config\ReservationStatus;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/espace-dj', name: 'dashboard_dj_')]
class DjDashboardController extends AbstractController
{
    public const MAX_ELEMENTS = 3;

    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_DJ')]
    public function index(
        ReservationRepository $reservationRepo,
    ): Response {
        /** @var User */
        $user = $this->getUser();

        return $this->render('dj_dashboard/index.html.twig', [
            'reservations' => $reservationRepo->findBy(
                ['artist' => $user->getArtist()],
                ['id' => 'desc'],
                self::MAX_ELEMENTS
            ),
            'newReservations' => $reservationRepo->findBy([], ['id' => 'desc'], self::MAX_ELEMENTS)
        ]);
    }

    #[Route('/reservation/{id}', name: 'show', methods: ['GET'])]
    #[IsGranted('ROLE_DJ')]
    public function show(
        Reservation $reservation,
    ): Response {

        return $this->render('dj_dashboard/reservation/show.html.twig', [
            'reservation' => $reservation
        ]);
    }

    #[Route('/reservation/{id}/accepter', name: 'accept_reservation', methods: ['POST'])]
    #[IsGranted('ROLE_DJ')]
    public function acceptReservation(
        Reservation $reservation,
        ManagerRegistry $doctrine,
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();
        if ($reservation->getStatus() === ReservationStatus::Waiting->name && $reservation->getArtist() === null) {
            $reservation->setArtist($user->getArtist());
            $reservation->setStatus(ReservationStatus::Validated->name);
            $entityManager->persist($reservation);
        }
        $entityManager->flush();

        return $this->redirectToRoute('dashboard_dj_show', ['id' => $reservation->getId()], Response::HTTP_SEE_OTHER);
    }
}
