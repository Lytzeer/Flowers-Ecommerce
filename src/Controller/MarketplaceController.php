<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarketplaceController extends AbstractController
{
    #[Route('/marketplace', name: 'app_marketplace', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
        }
        return $this->render('marketplace/index.html.twig', [
            'controller_name' => 'MarketplaceController',
            'articles' => $articleRepository->findAll(),
            'cart' => $cart ?? null,
        ]);
    }
}
