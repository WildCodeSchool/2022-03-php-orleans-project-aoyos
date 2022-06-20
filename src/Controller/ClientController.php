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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client', name: 'client_')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        Request $request,
        RequestStack $requestStack,
        ReservationRepository $reservationRepo,
        MailerInterface $mailer
    ): Response {
        $session = $requestStack->getSession();
        $reservation = $session->get('reservationForm') ?? new Reservation();

        $step = false;
        if ($session->has('isReservationClientInfosValid') && $session->get('isReservationClientInfosValid')) {
            $step = true;
            $form = $this->createForm(ReservationEventInfosType::class, $reservation);
        } else {
            $form = $this->createForm(ReservationClientInfosType::class, $reservation);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$step) {
                $session->set('reservationForm', $reservation);
                $session->set('isReservationClientInfosValid', true);
            } else {
                $session->remove('reservationForm');
                $session->remove('isReservationClientInfosValid');
                $reservationRepo->add($reservation, true);
                $this->sendReservationMail($reservation, $mailer);
            }
            return $this->redirectToRoute('client_index', ['_fragment' => 'reservation'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/index.html.twig', [
            'form' => $form,
            'step' => $step
        ]);
    }

    #[Route('/back', name: 'back')]
    public function backForm(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        if ($session->has('isReservationClientInfosValid')) {
            $session->remove('isReservationClientInfosValid');
        }
        return $this->redirectToRoute('client_index', ['_fragment' => 'reservation'], Response::HTTP_SEE_OTHER);
    }

    private function sendReservationMail(Reservation $reservation, MailerInterface $mailer): void
    {
            $email = (new Email())
                ->from($reservation->getEmail())
                ->to($this->getParameter('mailer_from'))
                ->subject('Une nouvelle demande de réservation')
                ->html($this->renderView('client/notification_email_reservation.html.twig', [
                    'reservation' => $reservation
                ]));

            $mailer->send($email);
    }
}
