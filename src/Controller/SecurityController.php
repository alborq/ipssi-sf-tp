<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/loginForm.html.twig', [
      'title' => 'Bet Rocket | Login',
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
  }

  public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
  {
    /** @var User $user */
    $form = $this->createForm(UserRegistrationType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user = $form->getData();
      $user->setRoles(['ROLE_USER']);
      $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();
      return $this->redirectToRoute('login');

    }

    return $this->render('security/registerForm.html.twig', [
      'UserRegistrationForm' => $form->createView()
    ]);
  }
}
