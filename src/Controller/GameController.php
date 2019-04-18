<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Game;
use App\Form\PlayType;
use App\Repository\GameRepository;
use App\Manager\UserManager;
use App\Manager\GameManager;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game")
     */
    public function index() : Response
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route("/game/play", name="game_play")
     * @param GameRepository $gameRepository
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function play(GameRepository $gameRepository, UserRepository $userRepository, Request $request) : Response
    {
        $lastGame = $gameRepository->findLast();
        if (!$lastGame) {
            throw new Exception('Il n\'y a pas de jeux');
        }

        $cases = $lastGame->getCases();
        $aCases = [];
        foreach ($cases as $row) {
            $aCases[ $row->getNumber() ] = $row->getColor();
        }
        $form = $this->createForm(PlayType::class, [
            'round'=> $lastGame->getId(),
            /** la mise par défault est égale à 10% du montant total du compte en banque de l'utilisateur */
            'mise'=>  ($this->getUser() ? $this->getUser()->getAmount() : 0 ) * (0.10),
            'numero'=> $aCases,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$this->getUser()) {
                return $this->redirectToRoute('game_play', ['message' => 'Vous n\'ête pas connecté']);
            }
            //-------------------------Déduction de mise + induction dans bank----------------------------------------
            $newAmount = ($this->getUser()->getAmount()) - ($data['mise']);
            $this->getUser()->setAmount($newAmount);
            $lastGame->setAmount($lastGame->getAmount() + $data['mise']);
            //--------------------------Prépare la mise pour la prochaine partie-----------------------------------
            $userManager = new UserManager();
            $numero = $userManager->getNumber($this->getUser()->getNextBet()).$data['case'];
            $mise   = $userManager->getMise($this->getUser()->getNextBet()).$data['mise'];
            $this->getUser()->setNextBet($numero.'-'.$mise);

            //--------------------------Game---------------------------------------

            $lastGame->addUser($this->getUser());
            //--------------------------Doctrine---------------------------------------
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->persist($lastGame);
            $entityManager->flush();
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }
            $msg = 'Vous avez misé '.$data['mise'].'$ sur la case '.$data['case'].
                ' pour la prochaine partie. Le montant de table s\'élevra à '.
                ($userManager->tableGain($userRepository)+$data['mise']).
                '$. Vous jouer pour un gain potentiel de '.($data['mise'] * 35);

            return $this->render('game/play.html.twig', [
                'form'      => $form->createView(),
                'cases'     => $cases ? : [],
                'message'   => $msg ? : ''
            ]);
        }

        return $this->render('game/play.html.twig', [
            'form'      => $form->createView(),
            'cases'     => $cases ? : [],
        ]);
    }

    /**
     * @Route("/game/run", name="game_run")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function run(UserRepository $userRepository) : Response
    {
        $game               = new Game();
        $userManager        = new UserManager();
        $gameManager        = new GameManager();

        $cases              = $game->getCases();
        $aPlayer            = $userRepository->nextPlayers();

        //----------------Lance la roue-------------------
        /** @var int $rand */
        $rand = array_rand($cases, 1);
        $finalResult = $cases[$rand];
        //------------------------------------------------

        $result = [];
        if (!is_null($aPlayer)) {
            foreach ($aPlayer as $player) {
                $numCase    = $userManager->getNumber($player->getNextBet());
                $betAmount  = $userManager->getMise($player->getNextBet());

                $numCase    = array_filter(explode(',', $numCase));
                $betAmount  = array_filter(explode(',', $betAmount));
                foreach ($numCase as $key => $case) {
                    if ($case == $finalResult->getNumber() && $cases[$case]->getColor() == $finalResult->getColor()) {
                        $result[$player->getId()] = $player->getUsername().'  à Gagné '.((int)$betAmount[$key] * 35);
                        $gameManager->payed($player, (int)$betAmount[$key] * 35);
                    } else {
                        $result[$player->getId()] = $player->getUsername() . ' à Perdu ';
                    }
                }
            }
            $result = array_merge(["tirage" => "Tirage -> ".$finalResult->getNumber().
                "(".$finalResult->getColor().")"], $result);
        }

        return $this->render('game/result.html.twig', ['result' => $result]);
    }

    /**
     * @Route("/game/mail", name="game_mail")
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function sendMAil(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('loryleticee@gmail.com')
            ->setSubject('Votre dernier pari')
            ->setTo('loryleticee@gmail.com')
            ->setBody(
                $this->renderView(
                    'mail/game.html.twig',
                    ['name' => 'rvtveveveve']
                ),
                'text/plain'
            )
            //->addPart('', 'text/html')
        ;
        $mailer->send($message);

        return $this->render('game/play.html.twig');
    }
}