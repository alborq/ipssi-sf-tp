<?php

declare(strict_types=1);
namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticlesType;
use App\Repository\ArticleRepository;
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
     * @Route(path="/index")
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
