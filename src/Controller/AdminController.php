<?php

namespace App\Controller;

use Date;
use App\Entity\Avis;
use App\Entity\Achat;
use App\Form\AvisType;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\ProduitType;
use App\Form\EditProduitType;
use App\Service\PanierService;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]// Protège les url


class AdminController extends AbstractController
{
    
 #[Route('/ajoutProduit', name: 'ajoutProduit')]
    public function ajoutProduit(Request $request, EntityManagerInterface $manager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();
            if ($photo){
                $photo_bdd = date('ymdHis') . uniqid() . $photo->getClientOriginalName();
                $photo->move($this->getParameter('upload_directory'), $photo_bdd);
                $produit->setPhoto($photo_bdd);
                $manager->persist($produit);
                $manager->flush();

                $this->addFlash('success' , 'produit ajouté');
                return $this->redirectToRoute('gestionProduit');
            }
        }

     return $this->render('admin/ajoutProduit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/gestionProduit', name: 'gestionProduit')]
    public function gestionProduit(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();


        return $this->render('admin/gestionProduits.html.twig', [
            'produits' => $produits
        ]);
    }

    
    #[Route('/editProduit/{id}', name: 'editProduit')]
    public function editProduit(Produit $produit, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(EditProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('editPhoto')->getData()) {
                $photo = $form->get('editPhoto')->getData();
                $photo_bdd = date('YmdHis') . uniqid() . $photo->getClientOriginalName();

                $photo->move($this->getParameter('upload_directory'), $photo_bdd);
                unlink($this->getParameter('upload_directory') . '/' . $produit->getPhoto ());
                $produit->setPhoto($photo_bdd);


            }

            $manager->persist($produit);
            $manager->flush();

            $this->addFlash('success', 'Produit modifié');
            return $this->redirectToRoute('gestionProduit');


        }


        return $this->render('admin/editProduit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit
        ]);
    }

    #[Route('/deleteProduit/{id}', name: 'deleteProduit')]
    public function deleteProduit(Produit $produit, EntityManagerInterface $manager): Response
    {
        $manager->remove($produit);
        $manager->flush();

        $this->addFlash('success', 'produit supprimé !!!');

        return $this->redirectToRoute('gestionProduit');
    }






    
}