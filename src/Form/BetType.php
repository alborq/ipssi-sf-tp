<?php

namespace App\Form;

use App\Entity\Bet;
use App\Entity\CaseGame;
use App\Entity\Game;
use App\Repository\CaseGameRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Security\Core\Security;

class BetType extends AbstractType
{
    private $securityContext;

    public function __construct(Security $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $amount = "";
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->securityContext->getToken()->getUser();
            $amount = $user->getAmount() - $user->getAmountBet();
        }
        $builder
            ->add('amount', IntegerType::class, [
                    'constraints' => new LessThanOrEqual($amount),
                ])
            ->add('type', ChoiceType::class, [
                    'choices' => [
                        'Color' => [
                            'Red' => 'Red',
                            'Black' => 'Black',
                        ],
                        'Parity' => [
                            'Odd' => 'Odd',
                            'Even' => 'Even',
                        ],
                        'Number' => [
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                            '11' => '11',
                            '12' => '12',
                            '13' => '13',
                            '14' => '14',
                            '15' => '15',
                            '16' => '16',
                            '17' => '17',
                            '18' => '18',
                            '19' => '19',
                            '20' => '20',
                            '21' => '20',
                            '22' => '20',
                            '23' => '20',
                            '24' => '20',
                            '25' => '20',
                            '26' => '20',
                            '27' => '20',
                            '28' => '20',
                            '29' => '20',
                            '30' => '20',
                            '31' => '20',
                            '32' => '20',
                            '33' => '20',
                            '34' => '20',
                            '35' => '20',
                            '36' => '20',
                        ],
                    ],
                ])
            ->add('game', EntityType::class, [
                    'class'         =>  Game::class,
                    'choice_label'  =>  function (Game $game) {
                        return $game->getName();
                    }
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bet::class,
        ]);
    }
}
