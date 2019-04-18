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
        $isOk=false;
        $newUserForm = $this->createForm(UserInscriptionType::class);
        $newUserForm->handleRequest($request);
        if ($newUserForm->isSubmitted() && $newUserForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $newUserForm->getData();
            $user->setRoles(['ROLE_USER']);
            $em->persist($newUserForm->getData());
            $em->flush();
            $isOk=true;
        }
        return $this->render('SignUp/addUser.html.twig', [
            'userInscriptionForm' => $newUserForm->createView(),
            'isOk' => $isOk
        ]);
    }
}
