<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findLatestArticles();
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
            $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles,
            'cart' => $cart ?? null,
            'carts' => $carts ?? null,
        ]);
    }
}