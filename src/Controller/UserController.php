<?php
declare(strict_types=1);
namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/index")
     */
    public function index()
        {
        // usually you'll want to make sure the user is authenticated first
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Call whatever methods you've added to your User class
        // For example, if you added a getFirstName() method, you can use that.
        return new Response('Well hi there '.$user->getUserName());
        }

    /**
     * @Route(path="/add")
     */
    public function add(Request $request): Response
    {
    $form = $this->createForm(UserType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // return $this->redirectToRoute('userapp_user_view', [
        //     'id' => $user->getId(),
        // ]);
    }
    return $this->render('user/add.html.twig', [
        'UserForm' => $form->createView()
    ]);
        
    }

    /**
     * @Route(path="/list")
     */
    public function list(): Response
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();

       
        return $this->render('user/user.html.twig', [
            'users' => $users
          
        ]);
    }
}
