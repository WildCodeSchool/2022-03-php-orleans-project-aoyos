<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Config\ReservationStatus;
use App\Repository\ReservationRepository;
use App\Service\DistanceCalculator;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/espace-dj', name: 'dashboard_dj_')]
class DjDashboardController extends AbstractController
{
    public const MAX_ELEMENTS = 3;

    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_USER')]
    public function index(
        ReservationRepository $reservationRepo,
        DistanceCalculator $distanceCalculator,
    ): Response {
        /** @var User */
        $user = $this->getUser();
        $reservations = $reservationRepo->findBy(
            ['artist' => $user->getArtist()],
            ['id' => 'desc'],
            self::MAX_ELEMENTS
        );
        $newReservations = $reservationRepo->findBy(
            [],
            ['id' => 'desc'],
            self::MAX_ELEMENTS
        );

        foreach ($reservations as $reservation) {
            $distance = $distanceCalculator->getDistance($user->getArtist(), $reservation);
            $reservation->setDistance($distance);
        }

        foreach ($newReservations as $newReservation) {
            $distance = $distanceCalculator->getDistance($user->getArtist(), $newReservation);
            $newReservation->setDistance($distance);
        }

        return $this->render('dj_dashboard/index.html.twig', [
            'reservations' => $reservations,
            'newReservations' => $newReservations,
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
        ValidatorInterface $validator
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();

        if ($reservation->getStatus() === ReservationStatus::Waiting->name && $reservation->getArtist() === null) {
            $reservation->setArtist($user->getArtist());
            $reservation->setStatus(ReservationStatus::Validated->name);
            $entityManager->persist($reservation);

            if (count($validator->validate($reservation)) === 0) {
                $entityManager->flush();

                $this->addFlash('success', 'L\'évènement vous a été attribué !');
            } else {
                $this->addFlash('danger', 'Cet évènement n\'est plus disponible.');
            }
        }
        return $this->redirectToRoute('dashboard_dj_show', ['id' => $reservation->getId()], Response::HTTP_SEE_OTHER);
    }
}
