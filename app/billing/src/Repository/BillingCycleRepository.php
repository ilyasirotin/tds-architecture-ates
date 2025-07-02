<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\BillingCycle;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BillingCycle>
 *
 * @method BillingCycle|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillingCycle|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillingCycle[]    findAll()
 * @method BillingCycle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillingCycleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillingCycle::class);
    }

    public function add(BillingCycle $cycle): BillingCycle
    {
        return $this->save($cycle);
    }

    public function findCurrentForAccount(Account $account): ?BillingCycle
    {
        // Find latest billing cycle
        return $this->findOneBy([
            'account' => $account->getId(),
        ], ['createdAt' => 'DESC']);
    }

    /**
     * @return array<BillingCycle>
     */
    public function findToClose(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('bc')
            ->from(BillingCycle::class, 'bc')
            ->leftJoin('bc.account', 'a')
            ->where('bc.status = :status')
            ->andWhere($qb->expr()->gt('a.balance', 0))
            ->andWhere($qb->expr()->between('bc.createdAt', ":startDate", ":now"))
            ->setMaxResults(1000)
            ->setParameter('status', BillingCycle::ACTIVE)
            ->setParameter('startDate', (new DateTime())->modify('-24 hours'))
            ->setParameter('now', new DateTime())
            ->getQuery()
            ->getResult();
    }

    public function open(Account $account): BillingCycle
    {
        $billingCycle = new BillingCycle();
        $billingCycle->setAccount($account);

        return $this->save($billingCycle);
    }

    public function close(BillingCycle $billingCycle): BillingCycle
    {
        $billingCycle->close();
        return $this->save($billingCycle);
    }

    private function save(BillingCycle $cycle): BillingCycle
    {
        $this->getEntityManager()->persist($cycle);
        $this->getEntityManager()->flush();

        return $cycle;
    }
}
