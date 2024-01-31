<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MarketplaceController extends AbstractController
{
    #[Route('/marketplace', name: 'app_marketplace', methods: ['GET', 'POST'])]
    public function index(ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request): Response
    {

        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
            $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);
        }

        $search = $request->request->get('search');

        if ($search != null) {
            $articles = $articleRepository->findByLike($search);
        } else {
            $articles = $articleRepository->findAll();
        }

        return $this->render('marketplace/index.html.twig', [
            'controller_name' => 'MarketplaceController',
            'articles' => $articles,
            'cart' => $cart ?? null,
            'carts' => $carts ?? null,
        ]);
    }
}
