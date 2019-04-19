<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="comment_article0_FK", columns={"id_article"}),
 * @ORM\Index(name="comment_user_FK", columns={"id_user"})})
 * @ORM\Entity
 */
class Comment
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
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=0, nullable=false)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_comment", type="date", nullable=false)
     */
    private $dateComment;

    /**
     * @var \Article
     *
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_article", referencedColumnName="id")
     * })
     */
    private $idArticle;

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

    public function getContent(): text
    {
        return $this->content;
    }

    public function setContent(text $content): self
    {
         $this->content = $content;
         return $this;
    }

    public function getDateArticle(): ?\DateTimeInterface
    {
        return $this->dateArticle;
    }
    public function setDateArticle(\DateTimeInterface $dateArticle): self
    {
        $this->dateArticle = $dateArticle;
        return $this;
    }

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }
}
