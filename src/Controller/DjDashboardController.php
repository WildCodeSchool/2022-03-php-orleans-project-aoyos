<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ArtistRepository;
use App\Repository\ReservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
}
