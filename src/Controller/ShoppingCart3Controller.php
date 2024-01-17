<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCart3Controller extends AbstractController
{
    #[Route('/cart3', name: 'app_shopping_cart3')]
    public function index(): Response
    {
        return $this->render('shopping_cart3/index.html.twig', [
            'controller_name' => 'ShoppingCart3Controller',
        ]);
    }
}
