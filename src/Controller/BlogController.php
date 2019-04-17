<?php

declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/blog")
 */
class BlogController extends AbstractController
{
    public function getAllArictles()
    {
        return $this->render('base.html.twig');
    }
}
