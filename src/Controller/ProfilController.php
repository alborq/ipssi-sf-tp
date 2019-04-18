<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilUserType;
use App\Form\PasswordUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfilController extends AbstractController
{
    public function profil(User $user, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->getUser()->getId() != $user->getId()) {
            $this->addFlash('danger', 'Arrete de vouloir me hacker');
            return $this->redirectToRoute('list_article');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ProfilUserType::class, $user);
        $form->handleRequest($request);

        $formPassword = $this->createForm(PasswordUserType::class, $user);
        $formPassword->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $form->getData();

            $user->setFirstname($newUser->getFirstname())
                 ->setLastname($newUser->getLastname())
                 ->setEmail($newUser->getEmail())
            ;
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour');
        }

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $newUserPassword= $formPassword->getData();

            $passwordEncoded  = $encoder->encodePassword($user, $newUserPassword->getPassword());
            $user->setPassword($passwordEncoded);

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour');
            return $this->redirectToRoute('logout_user');
        }

        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'profilForm' => $form->createView(),
            'formPassword' => $formPassword->createView()
        ]);
    }
}
