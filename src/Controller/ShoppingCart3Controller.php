<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Invoice;
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

        $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
        $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);

        $fname = $request->query->get('fname');
        $lname = $request->query->get('lname');
        $phone = $request->query->get('phone');
        $mail = $request->query->get('mail');
        $city = $request->query->get('city');
        $address = $request->query->get('address');
        $postal = $request->query->get('postal');

        if ($fname != null && $lname != null && $mail != null && $city != null && $address != null && $postal != null) {
            $invoice = new Invoice();
            $invoice->setUser($this->getUser());
            $invoice->setDate(new \DateTime());
            $invoice->setName($fname, " ", $lname);
            if ($phone != null) {
                $invoice->setPhone($phone);
            }
            $invoice->setMail($mail);
            $invoice->setCity($city);
            $invoice->setAddress($address);
            $invoice->setPostal($postal);
            $quantity = [];

            $totalprice = 0;

            foreach ($cart as $article) {
                $invoice->addArticle($entityManager->getRepository(Article::class)->find($article['id']));
            }

            foreach ($cart as $item) {
                foreach ($carts as $cart) {
                    if ($cart->getArticleId() == $item['id']) {
                        $totalprice = $totalprice + $item['price'] * $cart->getQuantity();
                        array_push($quantity, $cart->getQuantity());
                    }
                }
            }
            $invoice->setQuantity($quantity);

            $invoice->setPrice($totalprice);

            $entityManager->persist($invoice);
            $entityManager->flush();
        }

        foreach ($carts as $cart) {
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        $carts = $entityManager->getRepository(Cart::class)->findBy(['userId' => $this->getUser()->getId()]);

        return $this->render('shopping_cart3/index.html.twig', [
            'controller_name' => 'ShoppingCart3Controller',
            'cart' => null,
            'carts' => $carts ?? null,
            'invoice' => $invoice ?? null,
        ]);
    }
}
