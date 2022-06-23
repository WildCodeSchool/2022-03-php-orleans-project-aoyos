<?php

namespace App\Controller;

use App\Config\ReservationStatus;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservation', name: 'admin_reservation_')]
class AdminReservationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepo): Response
    {
        //Hack to retrieve enum values and use then in twig template
        $statusCases = ReservationStatus::cases();
        $statusColors = [];
        $statusValue = [];
        foreach ($statusCases as $case) {
            $statusColors[$case->name] = $case->getColor();
            $statusValue[$case->name] = $case->value;
        }

        return $this->render('admin/reservation/index.html.twig', [
            'reservations' => $reservationRepo->findBy(
                [],
                ['dateStart' => 'desc', 'status' => 'asc']
            ),
            'statusValue' => $statusValue,
            'statusColor' => $statusColors,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepo): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepo->add($reservation, true);

            return $this->redirectToRoute('admin_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Reservation $reservation,
        ReservationRepository $reservationRepo
    ): Response {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepo->add($reservation, true);

            return $this->redirectToRoute('admin_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Reservation $reservation,
        ReservationRepository $reservationRepo
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $reservationRepo->remove($reservation, true);
        }

        return $this->redirectToRoute('admin_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
