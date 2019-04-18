<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    public function list(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    public function detail(Article $article, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // AJOUT D'UNE VUE EN + A CHAQUE LECTURE D'ARTICLE
        $article->setView($article->getView() + 1);
        $entityManager->persist($article);
        $entityManager->flush();



        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTime('NOW'))
                ->setAuthor($this->getUser())
                ->setArticle($article)
                ->setIsCensured(false);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à bien été ajouté');
        }
        return $this->render('article/detail.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }
}
