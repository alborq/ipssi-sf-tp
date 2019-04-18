<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
  /**
   * @var string le token qui servira lors de l'oubli de mot de passe
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  protected $resetToken;
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;
  /**
   * @ORM\Column(type="string", length=180, unique=true)
   */
  private $email;
  /**
   * @ORM\Column(type="json")
   */
  private $roles = [];
  /**
   * @var string The hashed password
   * @ORM\Column(type="string")
   */
  private $password;
  /**
   * @ORM\Column(type="string", length=255)
   */
  private $nickname;
  /**
   * @ORM\Column(type="boolean")
   */
  private $isCertified = false;
  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $certifiedCode = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUsername(): string
  {
    return (string)$this->email;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getPassword(): string
  {
    return (string)$this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getSalt()
  {
    // not needed when using the "bcrypt" algorithm in security.yaml
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  /**
   * @return string|null
   */
  public function getNickname(): ?string
  {
    return $this->nickname;
  }

  /**
   * @param string $nickname
   * @return User
   */
  public function setNickname(string $nickname): self
  {
    $this->nickname = $nickname;

    return $this;
  }

  /**
   * @return bool|null
   */
  public function getIsCertified(): ?bool
  {
    return $this->isCertified;
  }

  /**
   * @param bool $isCertified
   * @return User
   */
  public function setIsCertified(bool $isCertified): self
  {
    $this->isCertified = $isCertified;

    return $this;
  }

  /**
   * @return string|null
   */
  public function getCertifiedCode(): ?string
  {
    return $this->certifiedCode;
  }

  /**
   * @param string $certifiedCode
   * @return User
   */
  public function setCertifiedCode(string $certifiedCode): self
  {
    $this->certifiedCode = $certifiedCode;

    return $this;
  }

  /**
   * @return string
   */
  public function getResetToken(): string
  {
    return $this->resetToken;
  }

  /**
   * @param string|null $resetToken
   */
  public function setResetToken(?string $resetToken): void
  {
    $this->resetToken = $resetToken;
  }
}
