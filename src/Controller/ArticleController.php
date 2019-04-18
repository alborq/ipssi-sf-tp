<?php
declare(strict_types=1);
namespace App\Controller;
use App\Entity;
use App\Repository;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/article")
 */
class ArticleController extends AbstractController
{

    /**
     * @Route(path="/add")
     */
    public function add(Request $request): Response
    {
        $isOk = false;
        $newArticleForm = $this->createForm(ArticleType::class);
        $newArticleForm->handleRequest($request);
        if ($newArticleForm->isSubmitted() && $newArticleForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newArticleForm->getData());
            $em->flush();
            $isOk = true;
        }
        return $this->render('article/add.html.twig', [
            'ArticleForm' => $newArticleForm->createView(),
            'isOk' => $isOk,
        ]);
    }

    /**
     * @Route(path="/view/{id}")
     */
    public function view(Article $article): Response
    {
        return $this->render('article/view.html.twig', [
           'article' => $article
        ]);
    }

    /**
     * @Route(path="/list")
     */
    public function list(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        return $this->render('article/list.html.twig', [
            'articles' => $repository->findAll()
        ]);
    }
}
