<?php

namespace App\Controller;

use App\Repository\ProductionRepository;
use App\Repository\PartnerRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public const MAX_ARTICLES = 4;

    #[Route('/', name: 'app_home')]
    public function index(
        ArticleRepository $articleRepository,
        PartnerRepository $partnerRepository,
        ProductionRepository $productionRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'articles' => $articleRepository->findBy([], ['createdAt' => 'DESC'], self::MAX_ARTICLES),
            'partners' => $partnerRepository->findAll(),
            'productions' => $productionRepository->findBy([], ['id' => 'DESC']),
        ]);
    }
}
