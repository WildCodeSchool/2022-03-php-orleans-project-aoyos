<?php

namespace App\Controller;

use App\Repository\ProductionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductionRepository $productionRepository): Response
    {
        $productions = $productionRepository->findBy([], ['id' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'productions' => $productions,
        ]);
    }
}
