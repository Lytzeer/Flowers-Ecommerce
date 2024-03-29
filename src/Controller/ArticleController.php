<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Cart;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $article = new Article($user->getId());
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
            $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);
        }
        $user = $entityManager->getRepository(User::class)->findUserById($article->getAuthorId());
        $reviews = $article->getReviews();

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($this->getUser());
            $review->setArticle($article);
            $review->setDate(new \DateTime());  
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/show.html.twig', [
            'author' => $user->getUsername(),
            'user' => $this->getUser(),
            'article' => $article,
            'reviews' => $reviews,
            'cart' => $cart ?? null,
            'carts' => $carts ?? null,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()->getId() !== $article->getAuthorId()) {
            return $this->redirectToRoute('app_home');
        }
        
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
            $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
            
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'cart' => $cart ?? null,
            'carts' => $carts ?? null,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        $carts = $entityManager->getRepository(Cart::class)->findBy(['articleId' => $article->getId()]);

        $userPic = $article->getUserPic();
        if ($userPic) {
            $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/'.$userPic);
        }

        foreach ($article->getReviews() as $review) {
            $entityManager->remove($review);
            $entityManager->flush();
        }
        foreach ($carts as $cart) {
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
