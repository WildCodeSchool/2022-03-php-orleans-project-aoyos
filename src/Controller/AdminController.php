<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\AdminArtistType;
use Symfony\Component\Mime\Email;
use App\Repository\ArtistRepository;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    public const MAX_ELEMENTS = 3;

    #[Route('/', name: 'index')]
    public function index(ArtistRepository $artistRepository, ReservationRepository $reservationRepo): Response
    {
        return $this->render('admin/index.html.twig', [
            'artists' => $artistRepository->findBy([], ['id' => 'DESC'], self::MAX_ELEMENTS),
            'reservations' => $reservationRepo->findBy([], ['id' => 'DESC'], self::MAX_ELEMENTS),
        ]);
    }

    #[Route('/dj/indisponibilite', name: 'dj_unavailability', methods: ['GET'])]
    public function djUnavailability(ArtistRepository $artistRepository): Response
    {
        return $this->render('admin/dj/unavailability.html.twig', [
            'artists' => $artistRepository->findBy([], ['firstname' => 'ASC']),
        ]);
    }

    #[Route('/dj/{id}', name: 'dj_show', methods: ['GET'])]
    public function show(Artist $artist): Response
    {
        return $this->render('admin/dj/show.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[Route('/dj', name: 'dj_list', methods: ['GET'])]
    public function djIndex(ArtistRepository $artistRepository, Request $request): Response
    {
        $form = $this->createForm(AdminArtistType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $artists = $artistRepository->findByCity($search);
        } else {
            $artists = $artistRepository->findBy([], ['id' => 'DESC']);
        }

        return $this->renderForm('admin/dj/index.html.twig', [
            'artists' => $artists,
            'form' => $form,
        ]);
    }

    #[Route('/dj/{id}/valider', name: 'dj_validate', methods: ['POST'])]
    public function validateDj(
        Request $request,
        Artist $artist,
        ManagerRegistry $doctrine,
        MailerInterface $mailer
    ): Response {
        $entityManager = $doctrine->getManager();

        if ($this->isCsrfTokenValid('validate' . $artist->getId(), $request->request->get('_token'))) {
            if (!in_array('ROLE_DJ', $artist->getUser()->getRoles())) {
                $artist->getUser()->setRoles(['ROLE_DJ']);

                $entityManager->flush();

                $this->addFlash('success', 'Vous avez accepté un nouveau DJ avec succès.');

                $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to($artist->getEmail())
                    ->subject('Votre profil est validé')
                    ->html($this->renderView('client/notification_email_reservation.html.twig', [
                        'artist' => $artist
                    ]));

                $mailer->send($email);
            } else {
                $this->addFlash('danger', 'Ce DJ existe déjà.');
            }
        }
        return $this->redirectToRoute('admin_dj_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/dj/{id}/desactiver', name: 'dj_deactivate', methods: ['POST'])]

    public function deactivateDj(
        Request $request,
        Artist $artist,
        ManagerRegistry $doctrine,
    ): Response {
        $entityManager = $doctrine->getManager();
        if ($this->isCsrfTokenValid('deactivate' . $artist->getId(), $request->request->get('_token'))) {
            $artist->getUser()->setRoles(['ROLE_USER']);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez désactivé ce DJ avec succès.');
        }
        return $this->redirectToRoute('admin_dj_list', [], Response::HTTP_SEE_OTHER);
    }
}
