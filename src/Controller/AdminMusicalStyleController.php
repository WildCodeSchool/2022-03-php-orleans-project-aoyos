<?php

namespace App\Controller;

use App\Entity\MusicalStyle;
use App\Form\MusicalStyle1Type;
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
            'musical_styles' => $musicStyleRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'admin_musical_style_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MusicalStyleRepository $musicStyleRepository): Response
    {
        $musicalStyle = new MusicalStyle();
        $form = $this->createForm(MusicalStyle1Type::class, $musicalStyle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musicStyleRepository->add($musicalStyle, true);

            return $this->redirectToRoute('admin_musical_style_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/musical_style/new.html.twig', [
            'musical_style' => $musicalStyle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_musical_style_show', methods: ['GET'])]
    public function show(MusicalStyle $musicalStyle): Response
    {
        return $this->render('admin/musical_style/show.html.twig', [
            'musical_style' => $musicalStyle,
        ]);
    }

    #[Route('/{id}/editer', name: 'admin_musical_style_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        MusicalStyle $musicalStyle,
        MusicalStyleRepository $musicStyleRepository
    ): Response {
        $form = $this->createForm(MusicalStyle1Type::class, $musicalStyle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musicStyleRepository->add($musicalStyle, true);

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

        return $this->redirectToRoute('admin_musical_style_index', [], Response::HTTP_SEE_OTHER);
    }
}
