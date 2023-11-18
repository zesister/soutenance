<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public $session;

    public $repository;

    public function __construct(RequestStack $session, ProduitRepository $repository)
    {
        $this->session=$session;
        $this->repository=$repository;

    }

    public function add(int $id)
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        if (empty($panier[$id])){

            $panier[$id]=1;
        }else{

            $panier[$id]++;

        }

        $session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        if (!empty($panier[$id]) && $panier[$id] !==1 ){

            $panier[$id]--;

        }else{

           unset($panier[$id]);

        }

        $session->set('panier', $panier);
    }


    public function delete(int $id)
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        if (!empty($panier[$id]) ){

            unset($panier[$id]);
        }

        $session->set('panier', $panier);
    }

    public function destroy()
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);
        if (!empty($panier) ){

            unset($panier);
        }
        $session->set('panier', []);

    }

    public function getFullPanier()
    {
        $session = $this->session->getSession();
        $panier=$session->get('panier', []);

        $panierDetail=[];

        foreach ($panier as $id => $quantite){

            $panierDetail[]=[
                'quantite'=>$quantite,
                'produit'=>$this->repository->find($id)
            ];

        }

        return $panierDetail;


    }


    public function getTotal()
    {

       $panier= $this->getFullPanier();

       $total=0;
       foreach ($panier as $indice => $item){

           $total+=$item['produit']->getPrix()*$item['quantite'];

       }
        return $total;

    }












}