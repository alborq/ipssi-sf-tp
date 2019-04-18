<?php

namespace App\Controller\Security;

use App\Entity\User;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller\Security
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws Exception
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $title = "Log in";
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        $user = new User();

        $formBuilder = $this->get('form.factory')->createNamedBuilder(null, FormType::class, $user);
        $formBuilder
            ->add('_username', TextType::class)
            ->add('_password', PasswordType::class)
            ->add('save', SubmitType::class)
        ;

        $form = $formBuilder->getForm();

        return $this->render('Security/manager.html.twig', array(
            'page'          => $title,
            'form'          => $form->createView(),
            'error'         => $error,
            'last_username' => $last_username,
        ));
    }

    /**
     * @Route("/register", name="app_register")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $title = "Sign up";
        $user = new User();
        $error = $authenticationUtils->getLastAuthenticationError();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder($user)
            ->add('_username', TextType::class)
            ->add('email', EmailType::class)
            ->add('amount', IntegerType::class)
            ->add('_password', PasswordType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));
            $user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Security/manager.html.twig', array(
            'page'      => $title,
            'form'      => $form->createView(),
            'error'     => $error,
        ));
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws Exception
     */
    public function logout()
    {
        return false;
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('home');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('home');
        } else {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

            /* @var $user User */
            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('home');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            $url = $this->generateUrl(
                'app_reset_password',
                array('token' => $token),
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $message = (new Swift_Message('Forgot Password'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    "blablabla voici le token pour reseter votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/forgotten_password.html.twig');
    }
}
