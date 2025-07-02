<?php

namespace App\Repository;

use App\Entity\Cost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cost>
 *
 * @method Cost|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cost|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cost[]    findAll()
 * @method Cost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cost::class);
    }

    public function add(Cost $cost): Cost
    {
        return $this->save($cost);
    }

    private function save(Cost $cost): Cost
    {
        $this->getEntityManager()->persist($cost);
        $this->getEntityManager()->flush();

        return $cost;
    }
}
