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

/**
 * Class GameController
 * @package App\Controller\Game
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="gameHome")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        $bet = new Bet();
        $Bets = "";
        $userConnected = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $Games  = $em->getRepository(Game::class)->findAll();

        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $idplayer = $em->getRepository(User::class)->find($userConnected->getId());
            $Bets  = $em->getRepository(Bet::class)->findBy(['player' => $idplayer]);
        }

        $form = $this->createForm(BetType::class, $bet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bet = $form->getData();

            $playerexist = false;
            foreach ($Games as $game) {
                foreach ($game->getPlayers() as $player) {
                    if ($player == $userConnected && $game == $bet->getGame()) {
                        $playerexist = true;
                    }
                }
            }

            if ($playerexist != true) {
                $game = $bet->getGame();
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
