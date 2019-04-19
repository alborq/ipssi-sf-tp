<?php

declare(strict_types=1);
namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticlesType;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BlogController
 * @package App\Controller
 * @Route(path="/",name="blog")
 */
class BlogController extends AbstractController
{
    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator) :Response
    {

        $em = $this->getDoctrine()->getManager();

        /** @var ArticleRepository $repository */
        $repository = $em->getRepository(Article::class);

        $articles = $repository->createQueryBuilder('a')
            ->orderBy('a.creationDate', 'DESC')
            ->getQuery();


        $articles = $paginator->paginate($articles, $request->query->getInt('page', 1), 10);


        return $this->render('Blog/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route(path="/show/{id}")
     */
    public function show(Request $request, int $id): Response
    {
        /** @var ArticleRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $article = $repository->find($id);

        if ($article === null) {
            throw $this->createNotFoundException();
        }

        /** @var CommentRepository $commentRepository */
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepository->findByArticleId($id);

           /*$newCommentForm = $this->createForm(CommentFormType::class);
        $newCommentForm->handleRequest($request);
        if($newCommentForm->isSubmitted() && $newCommentForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var CommentRepository $commentRepository */
            /*$commentRepository = $this->getDoctrine()->getRepository(Comment::class);

            $comments = $commentRepository->findByArticleId($id);

            foreach ($comments as $comment)
            {

            }

            $em->persist($newCommentForm->getData());
            $em->flush();
            $isOk = true;
        }*/
        return $this->render('ArticleController/view.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }
}




/*public function index(Request $request, PaginatorInterface $paginator)
{
    // Retrieve the entity manager of Doctrine
    $em = $this->getDoctrine()->getManager();

    // Get some repository of data, in our case we have an Appointments entity
    $appointmentsRepository = $em->getRepository(Appointments::class);

    // Find all the data on the Appointments table, filter your query as you need
    $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
        ->where('p.status != :status')
        ->setParameter('status', 'canceled')
        ->getQuery();

    // Paginate the results of the query
    $appointments = $paginator->paginate(
    // Doctrine Query, not results
        $allAppointmentsQuery,
        // Define the page parameter
        $request->query->getInt('page', 1),
        // Items per page
        5
    );

    // Render the twig view
    return $this->render('default/index.html.twig', [
        'appointments' => $appointments
    ]);
}*/
