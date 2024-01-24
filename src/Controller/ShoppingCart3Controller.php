<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Cart;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCart3Controller extends AbstractController
{
    #[Route('/cart3', name: 'app_shopping_cart3', methods: ['POST', 'GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
            $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);
            foreach ($carts as $cart) {
                $entityManager->remove($cart);
                $entityManager->flush();
            }
        }

        return $this->render('shopping_cart3/index.html.twig', [
            'controller_name' => 'ShoppingCart3Controller',
            'cart' => null,
        ]);
    }
}
