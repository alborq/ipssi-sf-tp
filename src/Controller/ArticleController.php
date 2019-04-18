<?php
declare(strict_types = 1);
namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticlesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route(path="/article",name="article")
 */
class ArticleController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     * @Route(path="/create")
     */
    public function create(Request $request): Response
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
    }
}
