<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/mes-evenements-{filtre}', name: 'my_events', requirements: ['filtre' => 'passes|a-venir'])]
    #[IsGranted('ROLE_DJ')]
    public function events(ReservationRepository $reservationRepo, string $filtre): Response
    {
        /** @var User */
        $user = $this->getUser();

        $condition = '';
        if ($filtre === 'passes') {
            $condition = '<';
        } elseif ($filtre === 'a-venir') {
            $condition = '>=';
        }

        $reservations = $reservationRepo->findByArtistByDate($user->getArtist(), $condition);

        return $this->render('dj_dashboard/my_events.html.twig', [
            'reservations' => $reservations,
            'filtre' => $filtre
        ]);
    }
}
