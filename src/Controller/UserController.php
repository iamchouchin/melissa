<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;

final class UserController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }
        
        $articles = $user->getArticles();

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'articles' => $articles
        ]);
    }
}
