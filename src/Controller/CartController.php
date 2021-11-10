<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    private function getQuantite($cart){
        $quantite = 0;
        foreach($cart as $item){
            $quantite = $quantite + $item['quantite'];
        }
        return $quantite;
    }

    private function getTotal($cart){
        $total = 0;
        foreach($cart as $item){
            $total = $total + ($item['quantite'] * $item['prix']);
        }
        return $total;
    }

    #[Route('/panier', name: 'cart')]
    public function index(): Response
    {
        /** @var  $session Recuperation de la session */
        $session = $this->requestStack->getSession();

        /** Recuperation du panier de l'existence du panier */
        $cart = $session->get('cart');

        /** Creation d'un array vide le cas echeant */
        if(is_null($cart)){
            $cart = [];
        }

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'nbProduct' => $this->getQuantite($cart),
            'cart' => $cart,
            'tva' => round($this->getTotal($cart)/1.2*0.2, 2),
            'total' => round($this->getTotal($cart), 2)
        ]);
    }

    #[Route('/update-panier', name: 'update_cart')]
    public function updateCart(): Response
    {
        /** @var  $session Recuperation de la session */
        $session = $this->requestStack->getSession();

        /** Recuperation du panier de l'existence du panier */
        $cart = $session->get('cart');

        /** Creation d'un array vide le cas echeant */
        if(is_null($cart)){
            $cart = [];
        }

        // TODO Sécuriser les données reçues de l'utilisateur

        foreach ($cart as $key => $values){
            if($_POST[$key.'-quantite'] != 0){
                $cart[$key] = [
                    'prix' => $_POST[$key.'-prix'],
                    'quantite' => $_POST[$key.'-quantite'],
                ];
            }
            else {
                unset($cart[$key]);
            }
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }
}
