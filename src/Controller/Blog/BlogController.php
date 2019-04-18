<?php


namespace App\Controller\Blog;

use App\Entity\Advert;
use App\Entity\Comment;
use App\Form\CommentType;
use DateTime;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller\Blog
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blogHome")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $allAppointmentsQuery  = $em->getRepository(Advert::class)->findAll();

        $appointments = $paginator->paginate(
            $allAppointmentsQuery,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render("Blog/index.html.twig", array(
            'listAdvert' => $appointments,
        ));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws Exception
     * @Route("/{id}", name="showAdvert")
     */
    public function showAdvert(Request $request, int $id)
    {
        $comment = new Comment();
        $em = $this->getDoctrine()->getManager();
        $advert  = $em->getRepository(Advert::class)->find($id);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setUser($this->get('security.token_storage')->getToken()->getUser());
            $comment->setAdvert($advert);
            $comment->setDate(new DateTime());
            $comment->setEnabled(true);
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('showAdvert', array('id' => $id));
        }

        return $this->render("Blog/show.html.twig", array(
            'advert' => $advert,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @param $state
     * @return RedirectResponse
     * @Route("/{state}/{id}", name="manageComment")
     */
    public function deleteComment($state, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mycomment = $em->getRepository(Comment::class)->find($id);

        if ($state == "hide") {
            $mycomment->setEnabled(false);
            $em->persist($mycomment);
            $em->flush();
        } else {
            $mycomment->setEnabled(true);
            $em->persist($mycomment);
            $em->flush();
        }


        return $this->redirectToRoute('blogHome');
    }
}
