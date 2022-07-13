<?php

namespace App\Controller;

use App\Repository\ProductionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/production', name: 'production_')]
class ProductionController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductionRepository $productionRepository): Response
    {
        return $this->render('production/index.html.twig', [
            'productions' => $productionRepository->findBy([], ['id' => 'DESC']),
        ]);
    }
}
