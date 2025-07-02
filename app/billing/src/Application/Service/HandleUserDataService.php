<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final class HandleUserDataService implements HandleUserDataUseCase
{
    private UserRepository $users;
    private EntityManagerInterface $em; // TODO: Try to opt out of this dependency
    private AccountRepository $accounts;

    public function __construct(
        UserRepository $users,
        AccountRepository $accounts,
        EntityManagerInterface $em
    )
    {
        $this->users = $users;
        $this->accounts = $accounts;
        $this->em = $em;
    }

    public function execute(HandleUserData $command): User
    {
        $existingUser = $this->users->findOneBy(['publicId' => $command->user()->getPublicId()]);

        if (isset($existingUser)) {
             return $existingUser;
        }

        return $this->em->wrapInTransaction(function () use ($command) {
            $newUser = $command->user();

            $account = (new Account())->setOwner($newUser);
            $this->accounts->add($account);

            $newUser->setAccount($account);
            $this->users->add($newUser);

            return $newUser;
        });
    }
}
