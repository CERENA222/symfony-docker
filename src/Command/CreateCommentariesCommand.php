<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Entity\Commentary;


#[AsCommand(
    name: 'app:CreateCommentaries',
    description: 'Create new Commentaries',
)]
class CreateCommentariesCommand extends Command
{
    protected ArticleRepository $articleRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nb_commentaire', InputArgument::REQUIRED, 'Nombre de commentaire')
            ->addArgument('id_article',  InputArgument::REQUIRED, 'Id de l\'article')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $idArticle= $input->getArgument('id_article');
        $article = $this->articleRepository->find($idArticle);

        if (!$article) {
            $io->error(sprint('impossible de trouver l\'article', $idArticle));
            return Command :: FAILURE;
        }

        $nbCommentaires = $input->getArgument('nb_commentaire');

        for($compteur = 0 ; $compteur < $nbCommentaires; $compteur++){
            $io->comment('Création commentaire ' . $compteur);
            $commentaire = new Commentary();
            $commentaire->setDate(new \DateTime());
            $commentaire->setContent('Description du commentaire ' . $compteur);
            $commentaire->setAuthor('Cerena BOSSIMBA');
            $commentaire->setArticleId($article);
            $this->entityManager->persist($commentaire);
           


        }
        $this->entityManager->flush();

        $io->success('commentaire créés.');

        return Command::SUCCESS;
    }
}
