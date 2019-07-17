<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{
    /*private $session;

    public function __construct(Session $session)
    {
       $this->session = $session;
    }*/

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation(CategorieRepository $catRepo, ProduitRepository $prodRepo, Session $session)
    {
//        $session = $this->session;

        if ($session->has('devis'))
            $devis = $session->get('devis');
        else
            $devis = false;


        return $this->render('home/devis/presentation.html.twig', [
            'categories' => $catRepo->findAll(),
            'produits'  => $prodRepo->findAll(),
            'devis' => $devis
        ]);
    }

    /**
     * @param $id
     * @param SessionInterface $session
     * @Route("/preparation/produit/{id}", name="produit")
     */
    public function preparationDevis($id, ProduitRepository $prodRepo, Session $session)
    {
//        $session = $this->session;
        if (!$session->has('devis'))
            $session->get('devis', []);

        /*$session->remove('devis');
        die();*/
        return $this->render('home/devis/preparation_devis.html.twig', [
            'produit' => $prodRepo->find($id),
            'devis' => $session->get('devis')
        ]);
    }

    /**
     * @param $id
     * @Route("ajout/produit/{id}", name="ajout_produit")
     */
    public function ajoutProduit($id, Request $request, Session $session)
    {
//        $session = $request->getSession();

        if (!$session->has('devis'))
            $session->set('devis', []);
        $devis = $session->get('devis');

        if (array_key_exists($id, $devis)) {
            if ($request->query->get('quantite') != null)
                $devis[$id] = $request->query->get('quantite');
                $session->getFlashBag()->add(
                  'success',
                  'La quantité a été modifié avec succès dans votre devis !'
                );
        } else {
            if ($request->query->get('quantite') != null)
                $devis[$id] = $request->query->get('quantite');
            else
                $devis[$id] = 30;
            $session->getFlashBag()->add(
                'success',
                'Le produit a été ajouté avec succès dans votre devis !'
            );
        }

        $session->set('devis', $devis);

        return $this->redirectToRoute('devis');
    }


    /**
     * @Route("/devis", name="devis")
     */
    public function devis(ProduitRepository $prodRepo, Session $session)
    {
//        $session = $this->session;
        if (!$session->has('devis'))
            $produits = 0;
        else
//            $produits = count($session->get('devis'));

        /*$session->remove('devis');
        die();*/
        $produits = $prodRepo->findArray(array_keys($session->get('devis')));

//        dd($session->get('devis'));

        return $this->render('home/devis/devis_en_ligne.html.twig',[
            'produits' => $produits,
            'devis' => $session->get('devis')
        ]);
    }

    /**
     * @param $id
     * @Route("supprimer/produit/{id}", name="supprimer_produit")
     */
    public function supprimerProduit($id, Session $session)
    {
//        $session = $this->session;
        $devis = $session->get('devis');

        if (array_key_exists($id, $devis))
        {
            unset($devis[$id]);
            $session->set('devis', $devis);
            $session->getFlashBag()->add(
                'success',
                'Le produit a bien été supprimé de votre devis'
            );
        }

        return $this->redirectToRoute('devis');
    }
}
