<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    #[Route('/update', name: 'app_update')]
    public function index(): Response
    {
        return $this->render('update/index.html.twig', [
            'controller_name' => 'UpdateController',
        ]);
    }
}
