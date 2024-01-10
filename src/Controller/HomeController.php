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
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($request->query->get('user_id')) {
            $cart = new Cart();
            $cart->setUserId($request->query->get('user_id'));
            $cart->setArticleId($request->query->get('article_id'));
    
            $entityManager->persist($cart);
            $entityManager->flush();
        }

        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $articles,
        ]);
    }
}