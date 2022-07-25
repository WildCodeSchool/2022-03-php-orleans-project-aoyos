<?php

namespace App\Controller;

use Exception;
use App\Entity\Document;
use App\Form\ArtistType;
use App\Service\Locator;
use App\Form\DocumentType;
use App\Form\ArtistProfileType;
use Symfony\Component\Mime\Email;
use App\Repository\ArtistRepository;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-dj', name: 'dashboard_dj_')]
class DjProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile', methods: ['GET', 'POST'])]
    public function index(
        ArtistRepository $artistRepository,
        Request $request,
        Locator $locator
    ): Response {
        /** @phpstan-ignore-next-line */
        $artist = $this->getUser()->getArtist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid())) {
            try {
                $locator->setCoordinates($artist);
                $this->addFlash('success', 'Votre profil a bien été modifié.');
            } catch (TransportException $te) {
                $this->addFlash('warning', 'Une erreur est survenue lors de la récupération de l\'adresse');
            } catch (Exception $e) {
                $this->addFlash('warning', $e->getMessage());
            }
            $artistRepository->add($artist, true);

            return $this->redirectToRoute('dashboard_dj_profile', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('dj_dashboard/profile/edit.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    #[Route('/documents', name: 'documents', methods: ['GET', 'POST'])]
    public function documents(
        Request $request,
        DocumentRepository $documentRepository,
        MailerInterface $mailer
    ): Response {
        /** @phpstan-ignore-next-line */
        $artist = $this->getUser()->getArtist();
        $document = $artist->getDocuments() ?? new Document();

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid())) {
            $document->setArtist($artist);
            $documentRepository->add($document, true);

            $email = (new Email())
                    ->from($artist->getEmail())
                    ->to($this->getParameter('mailer_from'))
                    ->subject('Nouveau profil Dj a validé')
                    ->html($this->renderView('dj/admin_completed_profile_email.html.twig', [
                        'artist' => $artist
                    ]));

                $mailer->send($email);

            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('dashboard_dj_documents', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dj_dashboard/profile/edit.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    #[Route('/artist', name: 'artist', methods: ['GET', 'POST'])]
    public function artist(
        Request $request,
        ArtistRepository $artistRepository,
    ): Response {
        /** @phpstan-ignore-next-line */
        $artist = $this->getUser()->getArtist();

        $form = $this->createForm(ArtistProfileType::class, $artist);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid())) {
            $artistRepository->add($artist, true);
            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('dashboard_dj_artist', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dj_dashboard/profile/edit.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }
}
