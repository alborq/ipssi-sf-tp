<?php

namespace App\Controller\Security;

use App\Entity\Advert;
use App\Entity\Game;
use App\Repository\AdvertRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller\Security
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @return Response
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository(Game::class)->findAll();

        $em = $this->getDoctrine()->getManager();

        /** @var AdvertRepository $repo */
        $repo = $em->getRepository(Advert::class);

        $allAppointmentsQuery = $repo->findAdvertByDate();
        return $this->render("Security/dashboard.html.twig", array(
            'games' => $games,
        ));
    }
}
