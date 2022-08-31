<?php

namespace App\Controller;


use DateTime;
use App\Entity\Produit;
use App\Form\ProduitFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route("/admin")]
class ProduitController extends AbstractController
{
    #[Route('/voir-les-produits', name: 'show_produits', methods: ['GET'])]
    public function showProduits(EntityManagerInterface $entityManager): Response
    {
        #Récupération en BDD de toutes les entités Produits, grace au repository
        $produits = $entityManager->getRepository(Produit::class)->findAll();

        return $this->render('admin/produit/show_produits.html.twig', [
            'produits' => $produits,
        ]);
    }

    # 1 - Créer un prototype de formulaire en ligne de commande ProduitFormType
    # 2 - Créer une action dans ProduitController pour la création d'un produit
    # 3 - Rendre la vue twig du formulaire
    # 4 - Créer le fichier Twig de cette vue.
    # 5 - Finir la partie POST dans l'action.

    #[Route('/ajouter-un-produit', name: 'create_produit', methods: ['GET', 'POST'])]
    public function createProduit(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
   
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitFormType::class, $produit)
                ->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){

                    $produit->setCreatedAt(new DateTime());
                    $produit->setUpdatedAt(new DateTime());


                    return $this->redirectToRoute('default_home');
            } //end if $photo
    

        return $this->render('admin/produit/create_produits.html.twig',[
            'form' => $form->createView(),
            
    ]);
    {
            $photo = $form->get('photo')->getData();

                    if($photo){

                        # 1 - Déconstruire le nom du fichier

                # a - On récupère l'extension grâce à la méthode guessExtension()
                $extension = '' . $photo->guessExtension();

                # 2 - Sécuriser le nom et reconstruire le nouveau nom de fichier
                # a - On assainit le nom du fichier pour supprimer les espaces et les accents.
                // $safeFilename = $slugger->slug($photo->getClientOriginalName());
                $safeFilename = $slugger->slug($photo->getClientOriginalName());
                // $safeFilename = $slugger->slug($produit->getTitle());

                # b - On reconstruit le nom du fichier.
                # uniqid() est une fonction native de PHP et génère un identifiant unique.
                # Cela evite les possibilités des doublons
                $newFilename = $safeFilename . '' . uniqid() . $extension;

                # 3 - Déplacer le fichier dans le bon dossier.
                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $produit->setPhoto($newFilename);
                }
                catch(FileException $exception) {
                    $this->addFlash('warning', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer en modifiant le produit.');
                    return $this->redirectToRoute('create_produit');
                
            } //end if photo
                
                $entityManager->persist($produit);
                $entityManager->flush();

                $this->addFlash('success','Le produit est en ligne avec succès');
                return $this->redirectToRoute('show_produits');

                // die ('Fichier moved');
        }//end if $form

        return $this->render('admin/produit/produit.html.twig', [
            'form_produit'=>$form->createView()
        ]);

    } //end function create

} //end class