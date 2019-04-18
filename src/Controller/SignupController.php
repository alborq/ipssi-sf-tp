<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserInscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     * @Route(path="/signUp")
     */
    public function signUp(Request $request): Response
    {
        $user = $this->createForm(UserInscriptionType::class)->handleRequest($request);
        if ($user->isSubmitted() && $user>isValid()) {
            $this->getDoctrine()->getManager()
                ->persist($user->getData())
                ->flush();
        }
        return $this->render('SignUp/addUser.html.twig', [
            'userInscriptionForm' => $user->createView(),
        ]);
    }
}
