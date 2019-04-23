<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $articles = $articleRepository->findAll();

        if (!empty($articles)) {
            return $this->render('blog/index.html.twig', array(
                'articles' => $articles,
            ));
        }

        return $this->render('blog/index.html.twig');

    }
}