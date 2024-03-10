<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Uid\Uuid;
use function Doctrine\ORM\QueryBuilder;

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
            return $this->findOneBy(['uuid' => Uuid::fromString($identifier)->toBinary()]);
        }
        return null;
    }

    /**
     * @param array<User> $excludedUsers
     * @param array<string> $excludedRoles
     * @return array<User>
     */
    public function findAssignees(array $excludedUsers, array $excludedRoles): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $excludedIds = array_reduce($excludedUsers, function ($prev, $user) {
            /** @var User $user */
            $prev[] = $user->getId();

            return $prev;
        }, []);

        // Stupid logic, just for save time
        return $qb
            ->from(User::class, 'u')
            ->select('u')
            ->where($qb->expr()->notIn('u.id', $excludedIds))
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }
}
