<?php

namespace App\Controller;

use App\Entity\Info;
use App\Entity\Produit;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{

    public function prestation(ObjectManager $manager, Session $session)
    {
        $info = $session->get('adresse');
        $devis = $session->get('devis');
        $agree_terms = $session->get('agree_terms');
        $adresse_reparation = $manager->getRepository(Info::class)->find($info['adresse_reparation']);
        $prestation = [];
        $forfaitH = 59.58;
        $totalHT = 0;
        $totalTVA = 0;
        $totalTTC = 0;
        $produits = $manager->getRepository(Produit::class)->findArray(array_keys($session->get('devis')));


        foreach ($produits as $produit)
        {
            $quantite = $devis[$produit->getId()]['quantite'];
            $couleur = $devis[$produit->getId()]['couleur'];
            $heure_travail = ($quantite / 30) * 7;
            $prixHT = $heure_travail * $forfaitH;
            $tva = $prixHT * 0.2;
            $prixTTC = $prixHT * 1.2;
            $totalHT += $prixHT;
            $totalTVA += $tva;
            $totalTTC += $prixTTC;

            $prestation['produit'][$produit->getId()] = [
                                                            'type_produit' => $produit->getNom(),
                                                            'quantite' => $quantite,
                                                            'couleur' => $couleur,
                                                            'temps_travail' => $heure_travail,
                                                            'prixHT' => $prixHT,
                                                            'tva' => $tva,
                                                            'prixTTC' => $prixTTC,
            ];

        }

        $prestation['adresse_reparation'] = [
                                    'adresse' => $adresse_reparation->getAdresse(),
                                    'bp' => $adresse_reparation->getCp(),
                                    'ville' => $adresse_reparation->getVille(),
                                    'pays' => $adresse_reparation->getPays()
        ];
        $prestation['details'] = [
                                    'date de reparation' => $agree_terms['date'],
                                    'agree_term' => $agree_terms['agree_term']
        ];

        $prestation['totalHT'] = round($totalHT, 2) ;
        $prestation['totalTVA'] = round($totalTVA, 2);
        $prestation['totalTTC'] = round($totalTTC, 2);
        $prestation['token'] = random_bytes(20);

//        dd($prestation);
        return $prestation;
    }

    /**
     * @Route("/prestation", name="prepare_prestation")
     */
    public function preparePrestation(Session $session, ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $utilisateur = $this->getUser();

        if (!$session->has('service'))
            $service = new Service();
        else
            $service = $manager->getRepository(Service::class)->find($session->get('service'));
//        dd($session);
        $service->setDate(new \DateTime())
                ->setUtilisateur($utilisateur)
                ->setReference(0)
                ->setPrestation($this->prestation($manager, $session))
        ;

        if (!$session->has('service'))
        {
            $manager->persist($service);
            $session->set('service', $service);
        }

        $manager->flush();

        $session->remove('devis');
        $session->remove('adresse');
        $session->remove('_csrf/adresse');
        $session->remove('agree_terms');
        $session->remove('_csrf/agree_terms');

        return $this->redirectToRoute('user_dashboard');
    }
}
