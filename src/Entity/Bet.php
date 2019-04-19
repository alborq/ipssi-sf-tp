<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bet
 *
 * @ORM\Table(name="bet", indexes={@ORM\Index(name="bet_game0_FK", columns={"id_game"}), 
 * @ORM\Index(name="bet_user_FK", columns={"id_user"})})
 * @ORM\Entity
 */
class Bet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="gain", type="integer", nullable=false)
     */
    private $gain;

    /**
     * @var \Game
     *
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_game", referencedColumnName="id")
     * })
     */
    private $idGame;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $title): self
    {
         $this->amount = $amount;
         return $this;
    }

    public function getGain(): ?int
    {
        return $this->gain;
    }

    public function setGain(int $gain): self
    {
         $this->gain = $gain;
         return $this;
    }

    public function getIdGame(): ?int
    {
        return $this->idGame;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }
}
