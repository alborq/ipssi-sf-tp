<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route(path="/admin")
 */
class HomeController extends AbstractController{

    /**
     * @Route(path="/number")
     */
    public function index() : Response
    {
        return $this->render(
            'home/home.html.twig', [
                'title' => 'Salut',
            ]
        );
    }
}