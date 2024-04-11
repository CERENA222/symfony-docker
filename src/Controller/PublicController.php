<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;

class PublicController extends AbstractController
{
        private ArticleRepository $articleRepository;
        public function __construct(ArticleRepository $articleRepository) {
            $this->articleRepository = $articleRepository;
        }
    

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
       $articles = $this->articleRepository->findAll();

        return $this->render('public/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    
    #[Route('/article/{id}', name: 'app_article')]
    public function article(int $id): Response
    {
       $article = $this->articleRepository->find($id);

        return $this->render('public/article.html.twig', [
            'article' => $article,
        

        ]);
    }


}
