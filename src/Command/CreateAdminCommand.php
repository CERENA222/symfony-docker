<?php
// src/Command/CreateAdminCommand.php



namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher )
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-admin')
            ->setDescription('Create a new admin user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the admin user')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of the admin user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']); 
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Admin user created successfully.');

        return Command::SUCCESS;
    }
}
