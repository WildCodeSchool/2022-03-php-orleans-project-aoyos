<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationClientInfosType;
use App\Form\ReservationEventInfosType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client', name: 'client_')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        Request $request,
        RequestStack $requestStack,
        ReservationRepository $reservationRepo
    ): Response {
        $reservation = new Reservation();

        $step = 0;
        $session = $requestStack->getSession();
        if ($session->has('step') && $session->get('step') === 1) {
            $step = 1;
            $form = $this->createForm(ReservationEventInfosType::class, $reservation);
        } else {
            $form = $this->createForm(ReservationClientInfosType::class, $reservation);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($step === 1) {
                $clientInfos = $session->get('reservationForm');
                $reservation->setLastname($clientInfos->getLastname());
                $reservation->setFirstname($clientInfos->getFirstname());
                $reservation->setCompany($clientInfos->getCompany());
                $reservation->setEmail($clientInfos->getEmail());
                $reservation->setPhone($clientInfos->getPhone());
                $reservationRepo->add($reservation, true);
            } else {
                $reservationSession = $form->getData();
                $session->set('reservationForm', $reservationSession);
                $session->set('step', 1);
                return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('client/index.html.twig', [
            'form' => $form,
            'step' => $step
        ]);
    }

    #[Route('/', name: 'back')]
    public function backForm(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        if ($session->has('step')) {
            $session->remove('step');
        }
        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }
}
