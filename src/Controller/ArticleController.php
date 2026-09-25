<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\Clock\DatePoint;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/ajouter-article', name: 'add_article')]
    public function addArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($user);

            $entityManager->persist($article);
            $entityManager->flush();


            return $this->redirectToRoute('app_user');
        }

        return $this->render('article/addArticle.html.twig', [
            'articleForm' => $form,
        ]);
    }

    /**
     * Action dédiée à la modification d'un article existant.
     */
    #[Route('/modifier-article/{id}', name: 'edit_article')]
    public function editArticle(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($article->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
        }

        return $this->render('article/addArticle.html.twig', [
            'articleForm' => $form,
            'isEdit' => true,
            'article' => $article,
        ]);
    }
}
