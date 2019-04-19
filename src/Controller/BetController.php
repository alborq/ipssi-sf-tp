<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Bet;
use App\Form\BetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bet")
 */
class BetController extends AbstractController
{
    /**
     * @Route("/index")
     */
    public function index()
    {
        return $this->render('bet/index.html.twig', [
            'controller_name' => 'BetController',
        ]);
    }
}
