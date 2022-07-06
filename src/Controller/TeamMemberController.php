<?php

namespace App\Controller;

use App\Repository\TeamMemberRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamMemberController extends AbstractController
{
    #[Route('/equipe', name: 'app_team')]
    public function index(TeamMemberRepository $teamMemberRepository, UserRepository $userRepository): Response
    {
        $artists = $userRepository->findByRole('ROLE_DJ');

        return $this->render('team/index.html.twig', [
            'team_members' => $teamMemberRepository->findBy([], ['name' => 'ASC']),
            'artists' => $artists,
        ]);
    }
}
