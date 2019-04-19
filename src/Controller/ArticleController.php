<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    public function list(PaginatorInterface $paginator, Request $request): Response
    {
       /**
        * @var \App\Repository\ArticleRepository $repo
       */
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $query = $repo->findAllQuery();
        $pagination = $paginator->paginate(
            $query, // query NOT result
            $request->query->getInt('page', 1), // Page number
            4 //Limt per page
        );
        return $this->render('article/index.html.twig', [
            'pagination' => $pagination
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
