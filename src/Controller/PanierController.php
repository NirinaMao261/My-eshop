<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{

    #[Route('/voir-mon-panier', name: 'show_panier', methods: ['GET'])]
    public function showPanier(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $total = 0;

        foreach($panier as $item) {

            # Pour chaque produit, on fait le total
            $totalItem = $item['produit']->getPrice() * $item['quantity'];

            # On ajoute ce total au montant final
            $total += $totalItem;
        }

        return $this->render('panier/show_panier.html.twig', [
            'total' => $total
        ]);
    }// end function showPanier()
}
    #[Route('/ajouter-un-produit/{id}', name: 'add_item', methods: ['GET'])]
    public function addItem(Produit $produit, SessionInterface $session): Response
    {
        # Si dans la $session le panier n'existe pas,
        # alors la méthode get() retournera le second paramètre, un array vide.
        $panier = $session->get('panier', []);

        if( !empty($panier[$produit->getId()]) ) {
            ++$panier[$produit->getId()]['quantity'];
        } else {
            $panier[$produit->getId()]['quantity'] = 1;
            $panier[$produit->getId()]['produit'] = $produit;
        }

        # Ici, nous devons set() le panier en session, en lui passant $panier[]
        $session->set('panier', $panier);

        $this->addFlash('success', 'Le produit a bien été ajouté à votre panier :)');
        return $this->redirectToRoute('default_home');
    }

    #[Route('/valider-mon-panier', name: 'validate_commande', methods:['GET'])]
    public function validateCommande(SessionInterface $session, EntityManagerInterface $entityManager): Response   
    {
        $panier = $session->get('panier', []);

        if(empty($panier)) {
            $this->addFlash('warning','Votre panier est vide');
            return $this->redirectToRoute('show_panier');
        }

    #[Route('/retirer-du-panier/{id}', name: 'delete_item', methods: ['GET'])]
    public function deleteItem(SessionInterface $session, int $id): Response
    {

        $panier - $session->get('panier', []);

            if (!empty($panier[$id])) {
                unset($panier[$id]);
            }

        $session->set('panier', $panier);

        $this->addFlash('success','Article supprimé');

        return $this->redirectToRoute('show_panier');
    }
        // $commande = new Commande();
        // $user =

        // $commande->setCreatedAt(new DateTime());
        // $commande->setUpdatedAt(new DateTime());

        // $total = 0;

}