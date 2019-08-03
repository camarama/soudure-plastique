<?php

namespace App\Controller;

use App\Repository\InfoRepository;
use App\Repository\UtilisateurRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/user/admin", name="user_")
 *
 * @IsGranted("ROLE_USER")
 */
class UserAdminController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function dashboard(Session $session)
    {
        $utilisateur = $this->getUser();

//        $infos = $infoRepo->findBy(['utilisateur' => $utilisateur]);


        dd($session);

        return $this->render('user_admin/admin_pages/user_dashbord.html.twig');
    }

    /**
     * @Route("/mailbox", name="mail")
     */
    public function mailbox()
    {
        return $this->render('');
    }
}
