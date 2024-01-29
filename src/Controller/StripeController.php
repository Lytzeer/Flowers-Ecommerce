<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart;
use Stripe\Stripe;
use Stripe\Checkout\Session;
 
#[Route('/stripe')]
class StripeController extends AbstractController
{
    #[Route('/', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }

    #[Route('/checkout', name: 'app_stripe_checkout', methods: ['POST', 'GET'])]
    public function checkout(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->getUser() != null) {
            $cart = $entityManager->getRepository(Cart::class)->findAllArticlesByUserId($this->getUser()->getId());
        }

        $fname = $request->request->get('fname');
        $lname = $request->request->get('lname');
        $phone = $request->request->get('phone');
        $mail = $request->request->get('mail');
        $city = $request->request->get('city');
        $address = $request->request->get('address');
        $postal = $request->request->get('postal');

        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        foreach ($cart as $article) {
            $checkout_session['line_items'][] = [
                'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => $article["price"] * 100,
                  'product_data' => [
                    'name' => $article["name"],
                    'images' => [$article["user_pic"]],
                  ],
                ],
                'quantity' => 1,
              ];
        }

        $checkout_session = Session::create([
            'line_items' => [
                $checkout_session['line_items']
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_shopping_cart3', [
                'fname' => $fname,
                'lname' => $lname,
                'phone' => $phone,
                'mail' => $mail,
                'city' => $city,
                'address' => $address,
                'postal' => $postal,
            ], 0, 'http://127.0.0.1:8000/cart3'),
            'cancel_url' => $this->generateUrl('app_cart', [], 0, 'http://127.0.0.1:8000/cart1'),
          ]);



        return $this->redirect($checkout_session->url, 303);
    }

    #[Route('/cancel', name: 'app_stripe_cancel')]
    public function cancel(): Response
    {
        return $this->render('stripe/cancel.html.twig', []);
    }
}