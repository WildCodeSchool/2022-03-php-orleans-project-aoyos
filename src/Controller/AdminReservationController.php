<?php

namespace App\Controller;

use App\Config\ReservationStatus;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Form\SearchAdminReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservation', name: 'admin_reservation_')]
class AdminReservationController extends AbstractController
{
    private array $statusColors = [];
    private array $statusValue = [];

    public function __construct()
    {
        $statusCases = ReservationStatus::cases();
        foreach ($statusCases as $case) {
            $this->statusColors[$case->name] = $case->getColor();
            $this->statusValue[$case->name] = $case->value;
        }
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, ReservationRepository $reservationRepo): Response
    {
        $form = $this->createForm(SearchAdminReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $status = $form->getData()['status'];
            if ($status === null && $search === null) {
                return $this->redirectToRoute('admin_reservation_index', [], Response::HTTP_SEE_OTHER);
            }
            $reservations = $reservationRepo->findLikeEventType($search, $status);
        } else {
            $reservations = $reservationRepo->findBy(['artist' => null], ['dateStart' => 'desc', 'status' => 'asc']);
        }

        return $this->renderForm('admin/reservation/index.html.twig', [
            'form' => $form,
            'reservations' => $reservations,
            'statusValue' => $this->statusValue,
            'statusColor' => $this->statusColors,
        ]);
    }

    #[Route('/validee', name: 'taken', methods: ['GET'])]
    public function takenReservations(Request $request, ReservationRepository $reservationRepo): Response
    {
        $form = $this->createForm(SearchAdminReservationType::class);
        $form->remove('status');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $reservations = $reservationRepo->findTakenWithSearch($search);
        } else {
            $reservations = $reservationRepo->findTaken();
        }

        return $this->renderForm('admin/reservation/index.html.twig', [
            'form' => $form,
            'reservations' => $reservations,
            'statusValue' => $this->statusValue,
            'statusColor' => $this->statusColors,
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

            $this->addFlash('success', 'Votre réservation a bien été éditée.');

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

        $this->addFlash('success', 'Votre réservation a bien été supprimée.');

        return $this->redirectToRoute('admin_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
