<?php

namespace App\Controller;

use App\Entity\TeamMember;
use App\Form\TeamMemberType;
use App\Repository\TeamMemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/equipe')]
class AdminTeamMemberController extends AbstractController
{
    #[Route('/', name: 'admin_team_index', methods: ['GET'])]
    public function index(TeamMemberRepository $teamMemberRepository): Response
    {
        return $this->render('admin/team_member/index.html.twig', [
            'team_members' => $teamMemberRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'admin_team_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamMemberRepository $teamMemberRepository): Response
    {
        $teamMember = new TeamMember();
        $form = $this->createForm(TeamMemberType::class, $teamMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamMemberRepository->add($teamMember, true);

            $this->addFlash('success', 'Votre collaborateur a bien été ajouté.');

            return $this->redirectToRoute('admin_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/team_member/new.html.twig', [
            'team_member' => $teamMember,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_team_member_show', methods: ['GET'])]
    public function show(TeamMember $teamMember): Response
    {
        return $this->render('admin/team_member/show.html.twig', [
            'team_member' => $teamMember,
        ]);
    }

    #[Route('/{id}/editer', name: 'admin_team_member_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        TeamMember $teamMember,
        TeamMemberRepository $teamMemberRepository
    ): Response {
        $form = $this->createForm(TeamMemberType::class, $teamMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamMemberRepository->add($teamMember, true);

            $this->addFlash('success', 'Votre collaborateur a bien été édité.');

            return $this->redirectToRoute('admin_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/team_member/edit.html.twig', [
            'team_member' => $teamMember,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_team_member_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        TeamMember $teamMember,
        TeamMemberRepository $teamMemberRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $teamMember->getId(), $request->request->get('_token'))) {
            $teamMemberRepository->remove($teamMember, true);
        }

        $this->addFlash('success', 'Votre collaborateur a bien été supprimé.');

        return $this->redirectToRoute('admin_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
