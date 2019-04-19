<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    //dd($this->getUser());
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

  public function register(Request $request, UserPasswordEncoderInterface $encoder, Swift_Mailer $mailer): Response
  {
    /** @var User $user */
    $form = $this->createForm(UserRegistrationType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $code = $this::generateRandomString();

      $user = $form->getData();
      $user->setRoles(['ROLE_USER']);
      $user->setCertifiedCode($code);
      $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();

      // Mail part
      $message = (new Swift_Message('Validate your account'));
      $message->setFrom('contact@betrocket.com');
      $message->setTo($user->getEmail());
      $message->setBody(
        $this->renderView(
          'email/registrationValidator.html.twig',
          [
            'nickname' => $user->getNickname(),
            'certification' => $code,
            'randomString' => $code
          ]
        ),
        'text/html'
      );

      $mailer->send($message);

      return $this->redirectToRoute('blog/index.html.twig');
    }

    return $this->render('security/registerForm.html.twig', [
      'UserRegistrationForm' => $form->createView()
    ]);
  }

  public function confirm(Request $request): Response
  {
    $certification = $request->attributes->get('certification');

    $form = $this->createForm(UserRegistrationType::class);

    $entitymanager = $this->getDoctrine()->getManager();

    $userRepository = $entitymanager->getRepository(User::class);

    $user = $userRepository->findBy(['certifiedCode' => $certification]);

    if (!empty($user)) {
      return $this->render('security/registerForm.html.twig', array(
        'UserRegistrationForm' => $form->createView(),
        'exist' => true
      ));
    }

    return $this->render('security/registerForm.html.twig', array(
      'UserRegistrationForm' => $form->createView(),
      'exist' => false
    ));
  }

  public function generateRandomString(int $length = 32, string $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
  {
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
