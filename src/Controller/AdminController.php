<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@Route(path="/admin",name="admin")
 */
class AdminController extends AbstractController
{

    /**
     *@Route(path="/display")
     */
    public function display() :Response
    {
        return $this->render('base.html.twig');
    }
}
