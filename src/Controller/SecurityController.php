<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class SecurityController extends AbstractController
{
    /**
     * @Route(path="/login")
     */
    public function login()
    {
        return $this->render('Security/login.html.twig');
    }
}