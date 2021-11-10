<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
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

    #[Route('/', name: 'index')]
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

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'nbProduct' => $this->getQuantite($cart)
        ]);
    }
}
