<?php
/**
 * Created by PhpStorm.
 * User: fbordjah
 * Date: 19/04/19
 * Time: 14:33
 */

namespace App\Entity;


class MyCase
{
    /**@var integer*/
private $num;
    /**@var integer*/
private $color;

    /**
     * MyCase constructor.
     * @param int $num
     * @param int $color
     */
    public function __construct(int $num, int $color)
    {
        $this->num = $num;
        $this->color = $color;
    }


    /**
     * @return int
     */
    public function getNum(): int
    {
        return $this->num;
    }

    /**
     * @param int $num
     */
    public function setNum(int $num): void
    {
        $this->num = $num;
    }

    /**
     * @return int
     */
    public function getColor(): int
    {
        return $this->color;
    }

    /**
     * @param int $color
     */
    public function setColor(int $color): void
    {
        $this->color = $color;
    }


}