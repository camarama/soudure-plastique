<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{
    /**
     * @Route("/devis", name="devis")
     */
    public function devis(CategorieRepository $catRepo)
    {
        return $this->render('home/devis/devis_en_ligne.html.twig', [
            'categories' => $catRepo->findAll()
        ]);
    }

    /**
     * @param $id
     * @param ProduitRepository $prodRepo
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("devis/{id}", name="bycategorie")
     */
    public function rechercheProduits($id, ProduitRepository $prodRepo)
    {
        return $this->json([
            'code' => 200,
            'message' => 'liste des produits par categorie',
            'produits' => $prodRepo->findBy([
                'categorie' => $id
            ])
        ], 200);
    }
}
