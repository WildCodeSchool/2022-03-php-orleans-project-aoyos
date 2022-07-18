<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Config\ReservationStatus;
use App\Entity\Unavailability;
use App\Form\ArtistBillType;
use Symfony\Component\Mime\Email;
use App\Form\SearchDjReservationsType;
use App\Form\UnavailabilityType;
use App\Repository\ReservationRepository;
use App\Repository\UnavailabilityRepository;
use App\Service\DistanceCalculator;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-dj', name: 'dashboard_dj_')]
class DjDashboardController extends AbstractController
{
    public const MAX_ELEMENTS = 4;

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
            ['status' => 'Validated', 'artist' => null],
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

    #[Route('/reservation/{id}', name: 'show', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DJ')]
    public function show(
        Reservation $reservation,
        ReservationRepository $reservationRepo,
        Request $request,
        MailerInterface $mailer
    ): Response {
        /** @var User */
        $user = $this->getUser();
        if ($reservation->getArtist() !== $user->getArtist() && $reservation->getArtist() !== null) {
            $this->denyAccessUnlessGranted('Accès interdit à cette réservation');
        }
        $form = $this->createForm(ArtistBillType::class, $reservation);
        $form->handleRequest($request);

        $now = new DateTime();

        if ($form->isSubmitted() && $form->isValid() && ($reservation->getDateEnd() < $now)) {
            $reservationRepo->add($reservation, true);

            $email = (new Email())
            ->from($user->getEmail())
            ->to($this->getParameter('mailer_from'))
            ->subject('Une nouvelle facture')
            ->html($this->renderView('dj_dashboard/notification_email_new_bill.html.twig', [
                'reservation' => $reservation,
                'user' => $user
            ]));

            $mailer->send($email);

            $this->addFlash('success', 'Votre facture a bien été enregistrée.');

            return $this->redirectToRoute(
                'dashboard_dj_show',
                ['id' => $reservation->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('dj_dashboard/reservation/show.html.twig', [
            'reservation' => $reservation,
            'form' => $form
        ]);
    }

    #[Route('/reservations', name: 'reservations', methods: 'GET')]
    #[IsGranted('ROLE_DJ')]
    public function reservations(
        ReservationRepository $reservationRepo,
        Request $request,
        DistanceCalculator $distanceCalculator
    ): Response {
        $form = $this->createForm(SearchDjReservationsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()['musicalStyle']) {
                $musicalStyleName = $form->getData()['musicalStyle']->getName();
                $reservations = $reservationRepo->findByMusicalStyle($musicalStyleName);
            } else {
                return $this->redirectToRoute('dashboard_dj_reservations', []);
            }
        } else {
            $reservations = $reservationRepo->findFreeEvent();
        }

        /** @var User */
        $user = $this->getUser();
        foreach ($reservations as $reservation) {
            $distance = $distanceCalculator->getDistance($user->getArtist(), $reservation);
            $reservation->setDistance($distance);
        }

        return $this->renderForm('dj_dashboard/reservation/index.html.twig', [
            'reservations' => $reservations,
            'form' => $form
        ]);
    }

    #[Route('/reservation/{id}/accepter', name: 'accept_reservation', methods: ['POST'])]
    #[IsGranted('ROLE_DJ')]
    public function acceptReservation(
        Reservation $reservation,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator,
        MailerInterface $mailer
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();

        if ($reservation->getStatus() === ReservationStatus::Validated->name && $reservation->getArtist() === null) {
            $reservation->setArtist($user->getArtist());
            $entityManager->persist($reservation);

            if (count($validator->validate($reservation)) === 0) {
                $entityManager->flush();

                $email = (new Email())
                    ->from($user->getEmail())
                    ->to($this->getParameter('mailer_from'))
                    ->subject('Du nouveau sur l\'espace DJ')
                    ->html($this->renderView('dj_dashboard/notification_email_reservation_validated.html.twig', [
                        'reservation' => $reservation
                    ]));

                $mailer->send($email);

                $this->addFlash('success', 'L\'évènement vous a été attribué !');
            } else {
                $this->addFlash('danger', 'Cet évènement n\'est plus disponible.');
            }
        }
        return $this->redirectToRoute('dashboard_dj_reservations', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reservation/{id}/annuler', name: 'cancel_reservation', methods: ['POST'])]
    #[IsGranted('ROLE_DJ')]
    public function cancelReservation(
        Reservation $reservation,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator,
        MailerInterface $mailer
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $entityManager = $doctrine->getManager();

        if (
            $reservation->getStatus() === ReservationStatus::Validated->name
            && $reservation->getArtist() === $user->getArtist()
        ) {
            $reservation->setArtist(null);
            $entityManager->persist($reservation);

            if (count($validator->validate($reservation)) === 0) {
                $entityManager->flush();

                $email = (new Email())
                    ->from($user->getEmail())
                    ->to($this->getParameter('mailer_from'))
                    ->subject('Du nouveau sur l\'espace DJ')
                    ->html($this->renderView('dj_dashboard/notification_email_reservation_canceled.html.twig', [
                        'reservation' => $reservation,
                        'artist' => $user->getArtist()
                    ]));

                $mailer->send($email);

                $this->addFlash('success', 'L\'évènement vous a été retiré !');
            }
        }
        return $this->redirectToRoute('dashboard_dj_reservations', [], Response::HTTP_SEE_OTHER);
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
            'passes' => ReservationRepository::PAST_EVENTS,
            'avenir' => ReservationRepository::FUTURE_EVENTS,
        ]);
    }

    #[Route('/indisponibilites', name: 'unavailability', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_DJ')]
    public function unavailabilityShowAdd(Request $request, UnavailabilityRepository $unavailabilityRepo): Response
    {
        /** @var User */
        $user = $this->getUser();

        $unavailabilities = $unavailabilityRepo->findBy(['artist' => $user->getArtist()], ['dateStart' => 'ASC']);

        $unavailability = new Unavailability();
        $form = $this->createForm(UnavailabilityType::class, $unavailability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unavailability->setArtist($user->getArtist());
            $unavailabilityRepo->add($unavailability, true);
            $this->addFlash('success', 'L\'indisponibilité a été ajoutée');
            return $this->redirectToRoute('dashboard_dj_unavailability', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('dj_dashboard/unavailability/index.html.twig', [
            'unavailabilities' => $unavailabilities,
            'unavailability' => $unavailability,
            'form' => $form,
        ]);
    }

    #[Route('/indisponibilites/{id}', name: 'unavailability_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Unavailability $unavailability,
        UnavailabilityRepository $unavailabilityRepo
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $unavailability->getId(), $request->request->get('_token'))) {
            $unavailabilityRepo->remove($unavailability, true);
        }

        $this->addFlash('success', 'Votre indisponibilité a bien été supprimée.');

        return $this->redirectToRoute('dashboard_dj_unavailability', [], Response::HTTP_SEE_OTHER);
    }
}
