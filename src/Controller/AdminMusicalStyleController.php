<?php

namespace App\Controller;

use App\Entity\MusicalStyle;
use App\Form\MusicalStyleType;
use App\Repository\MusicalStyleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/genres')]
class AdminMusicalStyleController extends AbstractController
{
    #[Route('/', name: 'admin_musical_style_index', methods: ['GET'])]
    public function index(MusicalStyleRepository $musicStyleRepository): Response
    {
        return $this->render('admin/musical_style/index.html.twig', [
            'musical_styles' => $musicStyleRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/nouveau', name: 'admin_musical_style_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MusicalStyleRepository $musicStyleRepository): Response
    {
        $musicalStyle = new MusicalStyle();
        $form = $this->createForm(MusicalStyleType::class, $musicalStyle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musicStyleRepository->add($musicalStyle, true);

            $this->addFlash('success', 'Votre genre musical a bien été ajouté.');

            return $this->redirectToRoute('admin_musical_style_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/musical_style/new.html.twig', [
            'musical_style' => $musicalStyle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editer', name: 'admin_musical_style_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        MusicalStyle $musicalStyle,
        MusicalStyleRepository $musicStyleRepository
    ): Response {
        $form = $this->createForm(MusicalStyleType::class, $musicalStyle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musicStyleRepository->add($musicalStyle, true);

            $this->addFlash('success', 'Votre genre musical a bien été édité.');

            return $this->redirectToRoute('admin_musical_style_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/musical_style/edit.html.twig', [
            'musical_style' => $musicalStyle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_musical_style_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        MusicalStyle $musicalStyle,
        MusicalStyleRepository $musicStyleRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $musicalStyle->getId(), $request->request->get('_token'))) {
            $musicStyleRepository->remove($musicalStyle, true);
        }

        $this->addFlash('success', 'Votre genre musical a bien été supprimé.');

        return $this->redirectToRoute('admin_musical_style_index', [], Response::HTTP_SEE_OTHER);
    }
}
