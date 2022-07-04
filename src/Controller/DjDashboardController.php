<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Config\ReservationStatus;
use App\Repository\ArtistRepository;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-dj', name: 'dashboard_dj_')]
class DjDashboardController extends AbstractController
{
    public const MAX_ELEMENTS = 3;

    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_USER')]
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

    #[Route('/mes-evenements/{filter}', name: 'my_events', requirements: ['filter' => 'passes|a-venir'])]
    #[IsGranted('ROLE_DJ')]
    public function events(ReservationRepository $reservationRepo, string $filter): Response
    {
        /** @var User */
        $user = $this->getUser();

        $reservations = $reservationRepo->findByArtistByDate($user->getArtist(), $filter);

        return $this->render('dj_dashboard/my_events.html.twig', [
            'reservations' => $reservations,
            'filter' => $filter,
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

    #[Route('/reservation/{id}/annuler', name: 'cancel_reservation', methods: ['POST'])]
    #[IsGranted('ROLE_DJ')]
    public function cancelReservation(
        Reservation $reservation,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();

        if ($reservation->getStatus() === ReservationStatus::Validated->name && $reservation->getArtist() === $user->getArtist()) {
            $reservation->setArtist(null);
            $reservation->setStatus(ReservationStatus::Waiting->name);
            $entityManager->persist($reservation);

            if (count($validator->validate($reservation)) === 0) {
                $entityManager->flush();

                $this->addFlash('success', 'L\'évènement vous a été retiré !');
            }
        }
        return $this->redirectToRoute('dashboard_dj_show', ['id' => $reservation->getId()], Response::HTTP_SEE_OTHER);
    }
}
