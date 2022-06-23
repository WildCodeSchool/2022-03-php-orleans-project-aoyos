<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\MusicalStyle;
use App\Entity\User;
use App\Form\ArtistProfileType;
use App\Form\ArtistRegistrationType;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dj', name: 'app_')]
class DjController extends AbstractController
{
    #[Route('/', name: 'dj')]
    public function index(): Response
    {
        return $this->render('dj/index.html.twig');
    }

    #[Route('/inscription', name: 'registration')]
    public function register(
        HttpFoundationRequest $request,
        ArtistRepository $artistRepository,
        RequestStack $stack,
        UserRepository $userRepository,
        HasherUserPasswordHasherInterface $passwordHasher
    ): Response {
        $session = $stack->getSession();
        $artist = $session->get('artist') ?? new Artist();
        $user = new User();

        if ($session->get('step') === 3) {
            $form = $this->createForm(ArtistRegistrationType::class, $user);
        } elseif ($session->get('step') === 2) {
            $form = $this->createForm(ArtistProfileType::class, $artist);
        } else {
            $form = $this->createForm(ArtistType::class, $artist);
            $session->set('step', 1);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($session->get('step') === 1) {
                $session->set('artist', $artist);
                $session->set('step', 2);
            } elseif ($session->get('step') === 2) {
                $session->remove('artist');
                $session->set('step', 3);
                $artistRepository->add($artist, true);
            } elseif ($session->get('step') === 3) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form['plainPassword']->getData()
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user, true);

                return $this->redirectToRoute('app_dj', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_registration', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'dj/registration/index.html.twig',
            [ 'form' => $form,
             'artist' => $artist,
            ]
        );
    }

    #[Route('/return', name: 'return')]
    public function return(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        if ($session->has('step')) {
            $session->remove('step');
        }
        return $this->redirectToRoute('app_registration', [], Response::HTTP_SEE_OTHER);
    }
}
