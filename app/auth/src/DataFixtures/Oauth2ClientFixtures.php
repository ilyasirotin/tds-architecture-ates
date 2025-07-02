<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

final class Oauth2ClientFixtures extends Fixture
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $sql = "
            insert into oauth2_client
                (identifier, name, secret, redirect_uris, grants, scopes, active, allow_plain_text_pkce)
            values
                (
                 'c27b1c0fce12bf92ebb497a4816c9c05',
                 'tasks_auth_client',
                 '321359b358b5591e97a51ca6cc77f35f032625daf34802e4549cbe5098aa7c2c52be8c9fad1b68c7f8ea463a5cf7ae253186e176e965aafdc33250fd129a8464',
                 'http://tasks.localhost/login/callback',
                 'authorization_code',
                 'profile',
                 true,
                 false
                ),
                (
                 '2c2ace50da12af470c6f6b5fd291d8ed',
                 'billing_auth_client',
                 'c107e8b880e26144eaa390b67ec05a9bb70d86478107ac274ce3784d0da1d24ac1b22dcc1cab193317f9d429410242748187aa83946416cd344f6051a9cc6863',
                 'http://billing.localhost/login/callback',
                 'authorization_code',
                 'profile',
                 true,
                 false
                );
        ";

        $this->entityManager
            ->getConnection()
            ->prepare($sql)
            ->executeQuery();
    }
}
