<?php

declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/blog",name="blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route(path="/articles",name="blog")
     */
    public function getAllArictles()
    {
        return $this->render('base.html.twig');
    }
}
