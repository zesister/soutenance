<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Achat;
use App\Form\AvisType;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\ProduitType;
use App\Service\PanierService;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    #[Route('/ajoutAvis/{id}', name: 'ajoutAvis')]
    public function ajoutAvis(Request $request, Produit $produit, EntityManagerInterface $manager): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){

            
            
                $manager->persist($avis);
                $manager->flush();

                $this->addFlash('success' , 'avis ajouté');
                return $this->redirectToRoute('gestionProduit');
            
        }

        return $this->render('admin/ajoutAvis.html.twig', [
            'form' => $form->createView(),
        ]);
    

    }

    
    #[Route('/ajoutPanier/{id}', name: 'ajoutPanier')]
    public function ajoutPanier(PanierService $panierService, $id): Response
    {
       $panierService->add($id);

        return $this->redirectToRoute('panier');
    }

    #[Route('/retraitPanier/{id}', name: 'retraitPanier')]
    public function retraitPanier(PanierService $panierService, $id): Response
    {
        $panierService->remove($id);


        return $this->redirectToRoute('panier');

    }


    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(PanierService $panierService, $id): Response
    {

            $panierService->delete($id);
        return $this->redirectToRoute('panier');
    }

    #[Route('/destroy', name: 'destroy')]
    public function destroy(PanierService $panierService): Response
    {
           $panierService->destroy();

        return $this->redirectToRoute('accueil');
    }

    #[Route('/panier', name: 'panier')]
    public function panier(PanierService $panierService): Response
    {
        $panier=$panierService->getFullPanier();
        $total=$panierService->getTotal();


        return $this->render('admin/panier.html.twig', [
           'panier'=>$panier,
            'total'=>$total
        ]);
    }
    




    #[Route('/', name: 'accueil')]
    public function accueil(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

       
     return $this->render('home/home.html.twig', [
            'produits'=> $produits

        ]);
    }




    #[Route('/commande', name: 'commande')]
    public function commande(EntityManagerInterface $manager, PanierService $panierService): Response
    {
        $panier=$panierService->getFullPanier();
        $commande=new Commande();
        $commande->setUtilisateur($this->getUser());
        $commande->setDate(new \DateTime());

        foreach ($panier as $item){
            $achat=new Achat();
            $achat->setProduit($item['produit']);
            $achat->setCommande($commande);
            $achat->setQuantite($item['quantite']);
            $manager->persist($achat);

        }
        $panierService->destroy();
        $manager->persist($commande);
        $manager->flush();


        $this->addFlash('success', 'Merci pour votre commande');

        return $this->redirectToRoute('accueil');
    }










}
