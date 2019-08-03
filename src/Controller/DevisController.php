<?php

namespace App\Controller;

use App\Entity\Info;
use App\Form\AdresseType;
use App\Form\AgreeTermsType;
use App\Form\DevisDataType;
use App\Repository\CategorieRepository;
use App\Repository\InfoRepository;
use App\Repository\ProduitRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{
    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation(CategorieRepository $catRepo, ProduitRepository $prodRepo, Session $session)
    {
//        $session = $this->session;
//        $session->clear();
//        die();
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
        /*$session->remove('devis');
        die();*/

        if (!$session->has('devis'))
            $session->set('devis', []);
        $devis = $session->get('devis');

//        dd($request->request->all());
        if (array_key_exists($id, $devis) && $request->isMethod('POST')) {
            if ($request->request->get('quantite') != null && $request->request->get('couleur') != null)
                $devis[$id] = $request->request->all();
                $session->getFlashBag()->add(
                  'success',
                  'La modification a été faite avec succès !'
                );
        } else {
            if ($request->request->get('quantite') != null && $request->request->get('couleur'))
                $devis[$id] = $request->request->all();
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

    /**
     * @Route("/devis", name="devis")
     */
    public function devis(ProduitRepository $prodRepo, Session $session)
    {
        /*$session->remove('devis');
        die();*/
//        dd($session);


        if (!$session->has('devis'))
        {
            $produits = 0;
            $session->getFlashBag()->add(
                'danger',
                'Il n\'y a pas de produit dans votre demande de devis, merci d\'ajouter un produit.'
            );
        } else
            $produits = $prodRepo->findArray(array_keys($session->get('devis')));

//        dd($session);


        return $this->render('home/devis/devis_en_ligne.html.twig',[
            'produits' => $produits,
            'devis' => $session->get('devis'),

        ]);
    }

    /**
     * @Route("/adresse", name="adresse")
     *
     * @IsGranted("ROLE_USER")
     */
    public function adresseReparation(Session $session, Request $request, ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateur = $this->getUser();

        if ($session->has('devis'))
        {
            if (count($session->get('devis')) == 0)
                return $this->redirectToRoute('presentation');
        }

        $adresse = new Info();
        $form = $this->createForm(AdresseType::class, $adresse);


        $form->handleRequest($request);

        if ($request->isMethod('POST'))
        {
            if ($form->isSubmitted() && $form->isValid())
            {
                $adresse->setUtilisateur($utilisateur);
                $manager->persist($adresse);
                $manager->flush();

                return $this->redirectToRoute('validation');
            }
        }

        return $this->render('home/devis/adresse.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    public function setAdresseOnSession(Session $session, Request $request)
    {
        if (!$session->has('adresse'))
            $session->set('adresse', []);
        $adresse = $session->get('adresse');

        if ($request->request->get('adresse_reparation') != null)
            $adresse['adresse_reparation'] = $request->request->get('adresse_reparation');
        else
            return $this->redirect($this->generateUrl('validation'));

        $session->set('adresse', $adresse);

        return $this->redirect($this->generateUrl('validation'));
    }

    /**
     * @Route("/validation", name="validation")
     *
     * @IsGranted("ROLE_USER")
     */
    public function validation(Session $session, Request $request, ProduitRepository $prodRepo, InfoRepository $infoRepo)
    {
        /*$session->clear();
        die();*/
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateur = $this->getUser();

        if ($request->getMethod() == 'POST')
            $this->setAdresseOnSession($session, $request);

        $adresse = $session->get('adresse');
        $adresse_reparation = $infoRepo->find($adresse['adresse_reparation']);

        if (!$session->has('agree_terms'))
            $session->set('agree_terms', []);
        $agree_terms = $session->get('agree_terms');

        $agreeform = $this->createForm(AgreeTermsType::class, $agree_terms);
        $agreeform->handleRequest($request);

        if ($agreeform->isSubmitted() && $agreeform->isValid())
        {
            if ($request->isMethod('POST'))
            {
                $agree_terms = $request->request->get('agree_terms');
                $session->set('agree_terms', $agree_terms);

//                dd($session);
                return $this->redirectToRoute('prepare_prestation');
            }
        }

        return $this->render('home/devis/validation.html.twig', [
            'adresse' => $adresse_reparation,
            'produits' => $prodRepo->findArray(array_keys($session->get('devis'))),
            'devis' => $session->get('devis'),
            'utilisateur' => $utilisateur,
            'agree_form' => $agreeform->createView(),
        ]);
    }
}
