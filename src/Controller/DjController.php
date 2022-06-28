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
use App\Service\DistanceCalculator;
use Doctrine\Persistence\ManagerRegistry;
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
        ManagerRegistry $doctrine,
        UserRepository $userRepository,
        HasherUserPasswordHasherInterface $passwordHasher,
        DistanceCalculator $distanceCalculator,
    ): Response {
        $session = $stack->getSession();
        $artist = $session->get('artist') ?? new Artist();
        $registryManager = $doctrine->getManager();
        $user = new User();

        if ($session->get('step') === 3) {
            $form = $this->createForm(ArtistRegistrationType::class, $user);
        } elseif ($session->get('step') === 2) {
            $registryManager->persist($artist);
            $form = $this->createForm(ArtistProfileType::class, $artist);
        } else {
            $session->set('step', 1);
            $form = $this->createForm(ArtistType::class, $artist);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($session->get('step') === 1) {
                $session->set('artist', $artist);
                $session->set('step', 2);
            } elseif ($session->get('step') === 2) {
                $session->set('step', 3);
            } elseif ($session->get('step') === 3) {
                $session->remove('step');
                $session->remove('artist');
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form['plainPassword']->getData()
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user, true);
                $artist->setUser($user);
                $distanceCalculator->setCoordinates($artist);
                $artistRepository->add($artist, true);


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
        if ($session->get('step') > 1) {
            $session->set('step', $session->get('step') - 1);
        } else {
            $session->remove('step');
        }
        return $this->redirectToRoute('app_registration', [], Response::HTTP_SEE_OTHER);
    }
}
