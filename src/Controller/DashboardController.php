<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart;
use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($this->getUser() != null) {
            $userId = $this->getUser()->getId();
            $articles = $entityManager->getRepository(Article::class)->findBy(['author_id' => $userId]);
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($userId);
        }

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'articles' => $articles ?? null,
            'cart' => $cart ?? null,
        ]);
    }
}
