<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route(path="/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     * @Route(path="/add")
     */
    public function addArticle(Request $request): Response
    {
        $isOk = false;
        $newArticleForm = $this->createForm(ArticleType::class);
        $newArticleForm->handleRequest($request);
        if($newArticleForm->isSubmitted() && $newArticleForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($newArticleForm->getData());
            $em->flush();
            $isOk = true;
        }
        return $this->render('Article/add.html.twig', [
            'isOk' => $isOk,
            'articleForm' => $newArticleForm->createView(),
            ]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return Response
     * @Route(path="/edit/{id}")
     */

    public function edit(Request $request, Article $article): Response
    {
        $isOk = false;
        $newArticleForm = $this->createForm(ArticleType::class, $article);
        $newArticleForm->handleRequest($request);
        if($newArticleForm->isSubmitted() && $newArticleForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $isOk = true;
        }

        return $this->render('Article/edit.html.twig', [
            'articleForm' => $newArticleForm->createView(),
            'isOk' => $isOk,
        ]);
    }


    /*public function delete(Article $article): Response
    {

    }*/

    /**
     * @return Response
     * @Route(path="/list/{page}" , requirements={"page" = "\d+"}, defaults={"page" = 1})
     */
    public function list($page): Response
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $listArticles = $this->indexAction($page);

        return $this->render('Article/list.html.twig', ['articles' => $listArticles, 'isOk' => true]);
    }

    /**
     * Liste l'ensemble des articles triés par date de publication pour une page donnée.
     *
     * @param int $page Le numéro de la page
     *
     * @return array
     */
    public function indexAction($page)
    {
        $nbArticlesParPage = $this->container->getParameter('front_nb_articles_par_page');

        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('XxxYyyBundle:Article')
            ->findAllPagineEtTrie($page, $nbArticlesParPage);

        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($articles) / $nbArticlesParPage),
            'nomRoute' => 'front_articles_index',
            'paramsRoute' => array()
        );

        return array(         
            'articles' => $articles,
            'pagination' => $pagination
        );
    }

    /**
     * @return Response
     * @param Article $article
     * @Route(path="/view/{id}")
     */
    public function viewArticle(Article $article): Response
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $viewArticle = $repository->find($article);
        if (!empty($viewArticle)){
            return $this->render('Article/view.html.twig', ['article' => $viewArticle]);
        }
        return $this->redirectToRoute('app_article_list', ['articles' => $repository->findAll(), 'isOk' => false]);
    }
}