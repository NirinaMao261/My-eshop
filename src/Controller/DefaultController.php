<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/home', name: 'default_home', methods: ['GET'])]
    public function home(EntityManagerInterface $entityManager): Response
    {
$produits= $entityManager->getRepository(Produits::class)->findBy(['deletedAt'=> null]);

        return $this->render('default/home.html.twig',[
            'produits' => $produits
    ]);

    }
}
