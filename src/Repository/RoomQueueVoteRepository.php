<?php

namespace App\Repository;

use App\Entity\RoomQueue;
use App\Entity\RoomQueueVote;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomQueueVote>
 */
class RoomQueueVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomQueueVote::class);
    }

    public function save(RoomQueueVote $vote, bool $flush = false): void
    {
        $this->_em->persist($vote);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(RoomQueueVote $vote, bool $flush = false): void
    {
        $this->_em->remove($vote);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findOneByQueueItemAndUser(RoomQueue $queueItem, User $user): ?RoomQueueVote
    {
        return $this->createQueryBuilder('vote')
            ->andWhere('vote.queueItem = :queue')
            ->andWhere('vote.user = :user')
            ->setParameters([
                'queue' => $queueItem,
                'user' => $user,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}

