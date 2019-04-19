<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /*public function comment()
    {

    }

    /**
     * @param Request $request
     * @return Response
     * @Route(path="/create")
     */
    /*public function create(Request $request): Response
    {
        $isOk=false;
        $newArticleForm = $this->createForm(ArticlesType::class);
        $newArticleForm->handleRequest($request);
        if ($newArticleForm->isSubmitted() && $newArticleForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newArticleForm->getData());
            $em->flush();
            $isOk=true;
        }
        return $this->render('ArticleController/createArt.html.twig', [
            'articleForm' => $newArticleForm->createView(),
            'isOk' => $isOk
        ]);
    }*/
}
