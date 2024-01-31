<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SellController extends AbstractController
{
    #[Route('/sell', name: 'app_sell')]
    public function index(EntityManagerInterface $entityManager, Request $request, UserInterface $user): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $article = new Article($user->getId());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('user_pic')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Error while uploading image');
                }

                $article->setUserPic($newFilename);
            }
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
            $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);
        }
        return $this->render('sell/index.html.twig', [
            'controller_name' => 'SellController',
            'cart' => $cart ?? null,
            'carts' => $carts ?? null,
            'article' => $article,
            'form' => $form,
        ]);
    }
}
