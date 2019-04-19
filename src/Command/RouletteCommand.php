<?php

namespace App\Command;

use App\Entity\Bet;
use App\Entity\CaseGame;
use App\Entity\Game;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RouletteCommand
 * @package App\Command
 */
class RouletteCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // On set le nom de la commande
        $this->setName('turn:roulette');

        // On set la description
        $this->setDescription("Permet de tourner la roulette");

        // On set l'aide
        $this->setHelp("Je serai affiche si on lance la commande bin/console app:test -h");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Game[] $allGames */
        $allGame = $em->getRepository(Game::class)->findAll();

        foreach ($allGame as $game) {
            $rand = random_int(0, 36);
            /** @var CaseGame $resultRoulette */
            $resultRoulette  = $em->getRepository(CaseGame::class)->findOneBy(['number' => $rand]);
            $game->setResult($resultRoulette);
            $em->persist($game);
        }
        $em->flush();

        foreach ($allGame as $game) {
            /** @var CaseGame $result */
            $result = $game->getResult();
            if ($result->getNumber() % 2 == 0) {
                $parity = "Even";
            } else {
                $parity = "Odd";
            }

            $output->writeln($result->getNumber()." ".$result->getColor().' ('.$parity.') !');

            /** @var Game $game */
            foreach ($game->getBets() as $bet) {
                if ($bet->getEnabled()) {
                    if ($bet->getType() == $result->getNumber() ||
                        $bet->getType() == $result->getColor() || $bet->getType() == $parity) {
                        $amountPlayer = $bet->getPlayer()->getAmount() + $bet->getAmount();
                        $amountGame = $bet->getGame()->getAmount() - $bet->getAmount();

                        $bet->getPlayer()->setAmount($amountPlayer);
                        $bet->getGame()->setAmount($amountGame);

                        $output->writeln($bet->getPlayer()->getUsername().
                            " a gagné en pariant sur ".$bet->getType().
                            ' (Table :'.$game->getName().') !');

                        $bet->setEnabled(false);
                        $em->persist($bet);
                    } else {
                        $amountPlayer = $bet->getPlayer()->getAmount() - $bet->getAmount();
                        $amountGame = $bet->getGame()->getAmount() + $bet->getAmount();

                        $bet->getPlayer()->setAmount($amountPlayer);
                        $bet->getGame()->setAmount($amountGame);

                        $output->writeln($bet->getPlayer()->getUsername().
                            " a perdu en pariant sur ".$bet->getType().
                            ' (Table :'.$game->getName().') !');

                        $bet->setEnabled(false);
                        $em->persist($bet);
                    }
                    $amountbet = $bet->getPlayer()->getAmountBet() - $bet->getAmount();
                    $bet->getPlayer()->setAmountBet($amountbet);
                }
            }
        }

        $em->flush();
    }
}
