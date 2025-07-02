<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function add(Task $task): Task
    {
        return $this->save($task);
    }

    public function update(Task $task): Task
    {
        return $this->save($task);
    }

    /**
     * @return array<Task>
     */
    public function findAssignedTasks(User $user): array
    {
        return $this->findBy(
            ['assignee' => $user->getId()],
            ['createdAt' => 'DESC'],
            100
        );
    }

    private function save(Task $task): Task
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();

        return $task;
    }
}
