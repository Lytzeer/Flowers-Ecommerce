<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Entity\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartCredentialsController extends AbstractController
{
    #[Route('/cart2', name: 'app_shopping_cart_credentials')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
        }
        return $this->render('shopping_cart_credentials/index.html.twig', [
            'controller_name' => 'ShoppingCartCredentialsController',
            'cart' => $cart ?? null,
        ]);
    }
}