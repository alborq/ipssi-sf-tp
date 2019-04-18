<?php

declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@Route(path="/blog",name="blog")
 */
class BlogController extends AbstractController
{
    /**
     *@Route(path="/articles")
     */
    public function getAllArictles() :Response
    {
        return $this->render('base.html.twig');
    }
}
