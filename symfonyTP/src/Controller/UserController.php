<?php
declare(strict_types=1);
namespace App\Controller;
use App\Entity\Users;
use App\Form\UserType;
use App\Repository\UsersRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * @Route(path="/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="/add")
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $player = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_add', [
                'id' => $player->getId(),
            ]);
        }

        return $this->render('Users/add.html.twig', [
            'UserType' => $form->createView()
        ]);
    }

}