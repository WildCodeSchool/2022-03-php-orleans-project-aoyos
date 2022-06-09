<?php

namespace App\Controller;

use App\Entity\Production;
use App\Form\ProductionType;
use App\Repository\ProductionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/production')]
class AdminProductionController extends AbstractController
{
    #[Route('/', name: 'app_admin_production_index', methods: ['GET'])]
    public function index(ProductionRepository $productionRepository): Response
    {
        return $this->render('admin/production/index.html.twig', [
            'productions' => $productionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_production_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductionRepository $productionRepository): Response
    {
        $production = new Production();
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productionRepository->add($production, true);

            return $this->redirectToRoute('app_admin_production_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/production/new.html.twig', [
            'production' => $production,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_production_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Production $production, ProductionRepository $productionRepository): Response
    {
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productionRepository->add($production, true);

            return $this->redirectToRoute('app_admin_production_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_production/edit.html.twig', [
            'production' => $production,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_production_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Production $production,
        ProductionRepository $productionRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $production->getId(), $request->request->get('_token'))) {
            $productionRepository->remove($production, true);
        }

        return $this->redirectToRoute('app_admin_production_index', [], Response::HTTP_SEE_OTHER);
    }
}
