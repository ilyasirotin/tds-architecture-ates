<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $user): User
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    public function loadUserByIdentifier(string $identifier): ?User
    {
        // Check if the identifier is an email address
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $this->findOneBy(['email' => $identifier]);
        }
        if (Uuid::isValid($identifier)) {
            return $this->findOneBy(['publicId' => Uuid::fromString($identifier)->toBinary()]);
        }
        return null;
    }

    /**
     * @param array<string> $excludedRoles
     * @return array<User>
     */
    public function findAssignees(array $excludedRoles): array
    {
        $rsm = $this->createResultSetMappingBuilder('u');

        // Dummy query just to save time
        $rawQuery = <<<SQL
            SELECT %s
            FROM "user" u
            WHERE NOT EXISTS(
                SELECT 1
                FROM json_array_elements_text(u.roles) r
                WHERE r IN (:roles)
            )
            limit 100;
        SQL;

        $query = $this->getEntityManager()->createNativeQuery(
            sprintf($rawQuery, $rsm->generateSelectClause()),
            $rsm
        );
        $query->setParameter('roles', $excludedRoles);

        return $query->getResult();
    }
}
