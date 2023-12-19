<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCart2Controller extends AbstractController
{
    #[Route('/cart2', name: 'app_cart2')]
    public function index(): Response
    {
        return $this->render('shopping_cart2/index.html.twig', [
            'controller_name' => 'ShoppingCart2Controller',
        ]);
    }
}
