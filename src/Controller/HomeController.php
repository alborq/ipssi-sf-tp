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
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $player = $security->getUser();
        }

        return $this->render("index.html.twig", array(
            'player'  => $player,
        ));
    }
}
