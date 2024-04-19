<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use Doctrine\ORM\EntityManagerInterface;


class PublicController extends AbstractController
{   private EntityManagerInterface $entityManager;
    private ArticleRepository $articleRepository;

        public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $entityManager) {
            $this->articleRepository = $articleRepository;
            $this->entityManager = $entityManager;
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
    public function article(int $id, Request $request): Response
    {
        $article = $this->articleRepository->find($id);
        $commentary = new Commentary();
        $commentary->setArticleId($article);
        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $commentary->setAuthor($this->getUser()->getEmail());
            
            $this->entityManager->persist($commentary);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_article', ['id' => $id]);
        }

        return $this->render('public/article.html.twig', [
            'article' => $article,
            'commentaryType' => $form->createView(),
        ]);
    }
}



