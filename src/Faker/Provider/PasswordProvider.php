<?php

namespace App\Faker\Provider;

use App\Entity\User;
use Faker\Generator;
use Faker\Provider\Base as BaseProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class PasswordProvider extends BaseProvider
{
    private $password;

    public function __construct(Generator $generator, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($generator);
        $user = new User();
        $this->password = $passwordEncoder->encodePassword(
            $user,
            'Password'
        );
    }


    public function passwordGenerator()
    {
        return $this->password;
    }
}
