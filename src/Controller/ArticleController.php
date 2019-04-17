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
         $article = $this->createForm(ArticlesType::class)->handleRequest($request);
        if($article->isSubmitted() && $article->isValid()){
            $this->getDoctrine()->getManager()
            ->persist($article->getData())
            ->flush();
        }
        return $this->render('ArticleController/createArt.html.twig', [
            'articleForm' => $article->createView(),
        ]);
    }
}