<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    /**
     * @param Request $request
     * @param Comment $comment
     * @param Article $article
     * @return Response
     */
    public function censor(Request $request, Comment $comment): Response
//    public function censor(Request $request, Comment $comment, Article $article): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {

            $comment->setIsCensored(true);
            $article = $comment->getArticle();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

        }

        return $this->redirectToRoute('article', [
            'id' => $article->getId(),
        ]);
    }
}
