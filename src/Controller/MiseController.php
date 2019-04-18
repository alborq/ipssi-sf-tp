<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Mise;
use App\Form\MiseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MiseController extends AbstractController
{

    public function miser(Request $request): Response
    {
        $mise = new Mise();
        $form = $this->createForm(MiseType::class, $mise);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $mise = $form->getData();
            $mise->setCreatedAt(new \DateTime("NOW"))
                ->setUser($this->getUser())
                ->setCheckMise(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mise);
            $entityManager->flush();
            $this->addFlash('success', 'Votre mise à bien été prise en compte');
        }
        return $this->render('mise/index.html.twig', [
            'controller_name' => 'MiseController',
            'form' => $form->createView(),
        ]);
    }


    public function checkGain(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $resultatRoulette = $this->turnRoulette();

        /**
         * @var Mise[] $miseNoCheck
         */
        $miseNoCheck = $this->getDoctrine()
            ->getRepository(Mise::class)->findBy(['checkMise' => false]);

        $resultat = [];
        foreach ($miseNoCheck as $mise) {
            if ($mise->getNumCase() == $resultatRoulette) {
                $mise->getUser()->setMoney($mise->getAmount());
                $resultat[] = [$mise->getUser(), $mise->getAmount(), "win" => true];
            } else {
                $resultat[] = [$mise->getUser(), $mise->getAmount(), "win" => false];
            }

            $mise->setCheckMise(true);
            $entityManager->persist($mise);
            $this->sendMail($mise->getUser());
        }

        //        $entityManager->flush();

        // Creer l'article uniquement si il y'a eu des gagnants ou des perdrants
        if (count($resultat) > 0) {
            $this->createArticle($resultat);
        }
        $this->addFlash('success', 'Les gains ont bien été attribué !');

        return $this->redirectToRoute("list_article");
    }


    private function turnRoulette()
    {
        // TODO: FAIRE TOURNER LA ROULETTE
        return 5;
    }

    private function createArticle($resulat)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $content = "";
        foreach ($resulat as $res) {
            if ($res["win"] === false) {
                $content .= "{$res[0]->getName()} à perdu <br>";
                continue;
            }
            $content .= "{$res[0]->getName()} à gagné <br>";
        }

        $article = new Article();
        $article->setTitle("LES GAGNANTS SONT !!!")
            ->setContent($content)
            ->setAuthor($this->getUser())
            ->setCreatedAt(new \DateTime('NOW'))
            ->setImg("https://static-news.moneycontrol.com/static-mcnews/2018/06/shutterstock_526404799-1-770x433.jpg")
            ->setView(0);
        $entityManager->persist($article);
        $entityManager->flush();
    }

    private function sendMail($user)
    {
        return;
    }
}
