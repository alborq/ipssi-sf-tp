<?php
/**
 * Created by IntelliJ IDEA.
 * User: AissatouDiop
 * Date: 4/15/19
 * Time: 17:38
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController

{
    public function test(): Response{

        $test="";

        return $this->render('home.html.twig', [
            'Hello World' => $test,
        ]);

    }

}