<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

class BoutiqueController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    private function getProductByPrice($prix, $cart){
        foreach($cart as $key => $value){
            if($value['prix'] == $prix){
                return $key;
            }
        }
        return false;
    }

    private function getQuantite($cart){
        $quantite = 0;
        foreach($cart as $item){
            $quantite = $quantite + $item['quantite'];
        }
        return $quantite;
    }

    #[Route('/boutique', name: 'boutique')]
    public function index(Request $request): Response
    {
        /** @var  $session Recuperation de la session */
        $session = $this->requestStack->getSession();

        /** Recuperation du panier de l'existence du panier */
        $cart = $session->get('cart');

        /** Creation d'un array vide le cas echeant */
        if(is_null($cart)){
            $cart = [];
        }

        /** @var  $form Creation du formulaire */
        $form = $this->createForm(OrderType::class);

        /** Controle du formulaire */
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var  $order Recuperation de la commande */
            $order = $form->getData();

            //TODO Sécurisation des données reçues

            /** Ajout des produits au panier  */
            $product = $this->getProductByPrice($order['prix'], $cart);
            if($product != false){
                $cart[$product]['quantite'] = $cart[$product]['quantite'] + $order['quantite'];
            }
            else {
                $cart[uniqid()] =  [
                    'prix' => $order['prix'],
                    'quantite' => $order['quantite']
                ];
            }

            /** Enregistrement de la session */
            $session->set('cart', $cart);

            /** @var  $response Retour d'une reponse JSON */
            $response = new Response(
                json_encode(
                    [
                        'nbProduct' => $this->getQuantite($cart)
                    ]
                )
            );

            return $response;
        }

        dump($cart);

        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'form' => $form->createView(),
            'nbProduct' => $this->getQuantite($cart)
        ]);
    }
}
