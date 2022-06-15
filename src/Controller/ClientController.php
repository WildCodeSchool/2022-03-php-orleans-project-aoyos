<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationClientInfosType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client', name: 'client_')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, ReservationRepository $reservationRepo): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationClientInfosType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepo->add($reservation, true);
            return $this->redirectToRoute('client_index');
        }

        return $this->renderForm('client/index.html.twig', [
            'form' => $form
        ]);
    }
}
