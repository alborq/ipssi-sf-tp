<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @param Security $security
     * @return Response
     * @Route("/", name="home")
     */
    public function index(Security $security)
    {
        $player = "";
        /** @var $userConnected User */
        $userConnected = $security->getUser();
        $em = $this->getDoctrine()->getManager();

        $Games  = $em->getRepository(Game::class)->findAll();

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $player = $em->getRepository(User::class)->find($userConnected->getId());
        }

        return $this->render("index.html.twig", array(
            'player'  => $player,
            'games'   => $Games,
        ));
    }
}
