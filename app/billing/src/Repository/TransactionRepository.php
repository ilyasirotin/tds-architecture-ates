<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function add(Transaction $transaction): Transaction
    {
        return $this->save($transaction);
    }

    /**
     * @return array<Transaction>
     */
    public function getDailyTransactionsLog(Account $account): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('t')
            ->from(Transaction::class, 't')
            ->where('t.account = :accountId')
            ->andWhere($qb->expr()->between('t.createdAt', ":startDate", ":now"))
            ->setMaxResults(1000)
            ->setParameter('accountId', $account->getId())
            ->setParameter('startDate', (new DateTime())->modify('-24 hours'))
            ->setParameter('now', new DateTime())
            ->getQuery()
            ->getResult();
    }

    private function save(Transaction $transaction): Transaction
    {
        $this->getEntityManager()->persist($transaction);
        $this->getEntityManager()->flush();

        return $transaction;
    }
}
