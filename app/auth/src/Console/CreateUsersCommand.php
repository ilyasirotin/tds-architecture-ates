<?php

declare(strict_types=1);

namespace App\Console;

use App\Application\Services\RegisterUser;
use App\Application\Services\RegisterUserUseCase;
use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:create-users',
    description: 'Creates default users',
    aliases: ['app:add-users'],
    hidden: false
)]
final class CreateUsersCommand extends Command
{
    private UserPasswordHasherInterface $passwordHasher;
    private RegisterUserUseCase $service;

    public function __construct(RegisterUserUseCase $service, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->passwordHasher = $passwordHasher;
        $this->service = $service;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $admin = new User();
            $admin->setEmail('admin@feather.com');
            $admin->setUsername('admin');
            $admin->setPublicId(Uuid::v7());
            $admin->setRoles([
                'ROLE_ADMIN'
            ]);
            $admin->setPassword(
                $this->passwordHasher->hashPassword($admin, '1234')
            );
            $this->service->execute(new RegisterUser($admin));

            $manager = new User();
            $manager->setEmail('manager@feather.com');
            $manager->setUsername('manager');
            $manager->setPublicId(Uuid::v7());
            $manager->setRoles([
                'ROLE_MANAGER'
            ]);
            $manager->setPassword(
                $this->passwordHasher->hashPassword($manager, '1234')
            );
            $this->service->execute(new RegisterUser($manager));

            $user1 = new User();
            $user1->setEmail('popug-1@feather.com');
            $user1->setUsername('popug-1');
            $user1->setPublicId(Uuid::v7());
            $user1->setRoles([
                'ROLE_USER',
            ]);
            $user1->setPassword(
                $this->passwordHasher->hashPassword($user1, '1234')
            );
            $this->service->execute(new RegisterUser($user1));

            $user2 = new User();
            $user2->setEmail('popug-2@feather.com');
            $user2->setUsername('popug-2');
            $user2->setPublicId(Uuid::v7());
            $user2->setRoles([
                'ROLE_USER'
            ]);
            $user2->setPassword(
                $this->passwordHasher->hashPassword($user2, '1234')
            );
            $this->service->execute(new RegisterUser($user2));

            $user3 = new User();
            $user3->setEmail('popug-3@feather.com');
            $user3->setUsername('popug-3');
            $user3->setPublicId(Uuid::v7());
            $user3->setRoles([
                'ROLE_USER',
            ]);
            $user3->setPassword(
                $this->passwordHasher->hashPassword($user3, '1234')
            );
            $this->service->execute(new RegisterUser($user3));

            $user4 = new User();
            $user4->setEmail('popug-4@feather.com');
            $user4->setUsername('popug-4');
            $user4->setPublicId(Uuid::v7());
            $user4->setRoles([
                'ROLE_USER'
            ]);
            $user4->setPassword(
                $this->passwordHasher->hashPassword($user4, '1234')
            );
            $this->service->execute(new RegisterUser($user4));
        } catch (\Throwable $e) {
            $output->writeln([
                'Failed!',
                'Exception occured:',
                $e->getMessage(),
                $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }

        $output->writeln([
            'Done!'
        ]);

        return Command::SUCCESS;
    }
}
