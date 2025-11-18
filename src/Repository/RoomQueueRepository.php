<?php

namespace App\Repository;

use App\Entity\Room;
use App\Entity\RoomQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomQueue>
 */
class RoomQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomQueue::class);
    }

    public function getNextPriorityForRoom(Room $room): int
    {
        $result = $this->createQueryBuilder('queue')
            ->select('MAX(queue.priority) as maxPriority')
            ->where('queue.room = :room')
            ->setParameter('room', $room)
            ->getQuery()
            ->getSingleScalarResult();

        $max = $result !== null ? (int) $result : 0;

        return $max + 1;
    }
}
