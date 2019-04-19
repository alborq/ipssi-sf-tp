<?php


namespace App\Controller\Game;

use App\Entity\Bet;
use App\Entity\Game;
use App\Entity\User;
use App\Form\BetType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class GameController
 * @package App\Controller\Game
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="gameHome")
     * @param Security $security
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Security $security, Request $request)
    {
        $bet = new Bet();
        $Bets = "";
        /** @var User $userConnected */
        $userConnected = $security->getUser();
        $em = $this->getDoctrine()->getManager();

        $Games  = $em->getRepository(Game::class)->findAll();

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $idPlayer = $em->getRepository(User::class)->find($userConnected->getId());
            $Bets  = $em->getRepository(Bet::class)->findBy(['player' => $idPlayer]);
        }

        $form = $this->createForm(BetType::class, $bet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bet = $form->getData();

            $playerExist = false;
            foreach ($Games as $game) {
                $players = $game->getPlayers();
                foreach ($players as $player) {
                    if ($player == $userConnected && $game == $bet->getGame()) {
                        $playerExist = true;
                    }
                }
            }

            if ($playerExist != true) {
                $game = $bet->getGame();
                /**
                 * @var $game Game
                 * @var $userConnected User
                 */
                $game->addPlayer($userConnected);
                $em->persist($game);
            }

            $nextAmount = $userConnected->getAmountBet()+ $bet->getAmount();
            $userConnected->setAmountBet($nextAmount);
            $bet->setPlayer($userConnected);
            $bet->setGame($bet->getGame());
            $bet->setAmount($bet->getAmount());
            $bet->setEnabled(true);
            $em->persist($bet);

            $em->flush();

            return $this->redirectToRoute("gameHome");
        }

        return $this->render("Game/index.html.twig", array(
            'form'  => $form->createView(),
            'games' => $Games,
            'bets'  => $Bets,
        ));
    }
}
