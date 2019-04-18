<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route(path="/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route(path="/test", name="test")
     */
    public function test(\Swift_Mailer $mailer): Response
    {
        $mail = new \Swift_Message();

        $mail->setFrom('tim.cook@apple.com');
        $mail->setTo('nous@ipssi.fr');
        $mail->setSubject('Vous êtes super');

        $mail->setBody($this->render('mail/demo.html.twig'), 'text/html');
        $mail->addPart($this->render('mail/demo.txt.twig'), 'text/plain');

        $mailer->send($mail);

        return new Response('test');
    }
}

// .env
// MAILER_URL=smtp://mailhog:1025
