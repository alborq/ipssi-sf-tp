<?php

namespace App\Faker\Provider;

use App\Entity\User;
use Faker\Generator;
use Faker\Provider\Base as BaseProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class CaseProvider extends BaseProvider
{
    const COLOR_PROVIDER = [
        'Red',
        'Black',
        'Vert',
    ];

    const NUMBER_PROVIDER = [
        32,15,19,4,21,2,25,17,34,6,27,13,36,11,30,8,
        23,10,5,24,16,33,1,20,14,31,9,22,18,29,7,28,12,35,3,26,0
    ];


    public function number($id)
    {
        return self::NUMBER_PROVIDER[$id];
    }

    public function color($id)
    {
        if ($id == 36) {
            $color = self::COLOR_PROVIDER[2];
        } else {
            if ($id % 2 == 0) {
                $color = self::COLOR_PROVIDER[0];
            } else {
                $color = self::COLOR_PROVIDER[1];
            }
        }

        return $color;
    }
}
