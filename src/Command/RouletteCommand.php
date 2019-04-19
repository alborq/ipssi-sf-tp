<?php

namespace App\Command;

use App\Entity\Advert;
use App\Entity\CaseGame;
use App\Entity\Game;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

/**
 * Class RouletteCommand
 * @package App\Command
 */
class RouletteCommand extends ContainerAwareCommand
{
    private $mailer;
    private $twig;

    public function __construct(Swift_Mailer $mailer, Environment $twig)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

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
        $allGame = $em->getRepository(Game::class)->findAll();
        $admin = $em->getRepository(User::class)->findOneBy(['email' => 'admin@admin.fr']);

        /** @var Game $game */
        foreach ($allGame as $game) {
            $rand = random_int(0, 36);
            /** @var CaseGame $resultRoulette */
            $resultRoulette  = $em->getRepository(CaseGame::class)->findOneBy(['number' => $rand]);
            $game->setResult($resultRoulette);
            $em->persist($game);
        }
        $em->flush();

        /** @var Game $game */
        foreach ($allGame as $game) {
            /** @var CaseGame $result */
            $result = $game->getResult();
            if ($result->getNumber() % 2 == 0) {
                $parity = "Even";
            } else {
                $parity = "Odd";
            }

            $output->writeln($result->getNumber()." ".$result->getColor().' ('.$parity.') !');

            foreach ($game->getBets() as $bet) {
                if ($bet->getEnabled()) {
                    if ($bet->getType() == $result->getNumber() ||
                        $bet->getType() == $result->getColor() || $bet->getType() == $parity) {
                        $amountPlayer = $bet->getPlayer()->getAmount() + $bet->getAmount();
                        $amountGame = $bet->getGame()->getAmount() - $bet->getAmount();

                        $bet->getPlayer()->setAmount($amountPlayer);
                        $bet->getGame()->setAmount($amountGame);

                        $bet->setEnabled(false);
                        $em->persist($bet);

                        $output->writeln($bet->getPlayer()->getUsername().
                            " a gagné en pariant sur ".$bet->getType().
                            ' (Table :'.$game->getName().') !');

                    // Mailer
                    //------------------------------------------------------------------
                        $message = (new Swift_Message('Vous avez gagner !!!'))
                            ->setFrom('adminSymfony@admin.fr')
                            ->setTo($bet->getPlayer()->getEmail())
                            ->setBody(
                                "Vous avez gagner ".$bet->getAmount()
                                ." € en pariant sur ".$bet->getType()
                                ." de la Table : ".$game->getName(),
                                'text/html'
                            );

                        $this->mailer->send($message);
                    //------------------------------------------------------------------

                        $advertLoose = new Advert();
                        $advertLoose->setTitle($bet->getPlayer()->getUsername()." won ".$bet->getAmount());
                        $advertLoose->setContent($bet->getPlayer()->getUsername()
                            ." won ".$bet->getAmount()." by beting on ".$bet->getType()
                            ." (".$game->getName().")");
                        $advertLoose->setReleaseDate(new DateTime());
                        $advertLoose->setCommentEnabled(true);
                        $advertLoose->setAuthor($admin);
                        $em->persist($advertLoose);
                        $em->flush();
                    } else {
                        $amountPlayer = $bet->getPlayer()->getAmount() - $bet->getAmount();
                        $amountGame = $bet->getGame()->getAmount() + $bet->getAmount();

                        $bet->getPlayer()->setAmount($amountPlayer);
                        $bet->getGame()->setAmount($amountGame);

                        $bet->setEnabled(false);
                        $em->persist($bet);

                        $output->writeln($bet->getPlayer()->getUsername().
                            " a perdu en pariant sur ".$bet->getType().
                            ' (Table :'.$game->getName().') !');

                    // Mailer
                    //------------------------------------------------------------------
                        $message = (new Swift_Message('Vous avez perdu ...'))
                            ->setFrom('adminSymfony@admin.fr')
                            ->setTo($bet->getPlayer()->getEmail())
                            ->setBody(
                                "Vous avez perdu ".$bet->getAmount()
                                ." € en pariant sur ".$bet->getType()
                                ." de la Table : ".$game->getName(),
                                'text/html'
                            );

                        $this->mailer->send($message);
                    //------------------------------------------------------------------

                        $advertLoose = new Advert();
                        $advertLoose->setTitle($bet->getPlayer()->getUsername()." lost ".$bet->getAmount());
                        $advertLoose->setContent($bet->getPlayer()->getUsername()
                            ." lost ".$bet->getAmount()." by beting on ".$bet->getType()
                            ." (".$game->getName().")");
                        $advertLoose->setReleaseDate(new DateTime());
                        $advertLoose->setCommentEnabled(true);
                        $advertLoose->setAuthor($admin);
                        $em->persist($advertLoose);
                        $em->flush();
                    }
                    $amountbet = $bet->getPlayer()->getAmountBet() - $bet->getAmount();
                    $bet->getPlayer()->setAmountBet($amountbet);
                }
            }
        }

        $em->flush();
    }
}
