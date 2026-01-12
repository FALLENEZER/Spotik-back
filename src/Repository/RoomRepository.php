<?php

namespace App\Repository;

use App\Entity\Room;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(private EntityManagerInterface $em ,ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function store(Room $room, $isFlush = true): Room
    {
        $this->em->persist($room);
    }

    public function leave(Room $room, User $user, $isFlush = true): Room
    {
        $room->removeMember($user);
        if ($isFlush) {
            $this->em->flush();
        }

        return $room;
    }

    public function join(Room $room, User $user, $isFlush = true): Room
    {
        $room->addMember($user);
        if ($isFlush) {
            $this->em->flush();
        }

        return $room;
    }
    //    /**
    //     * @return Room[] Returns an array of Room objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Room
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function delete(Room $room, $isFlush = true): void
    {
        $this->em->remove($room);

        if ($isFlush) {
            $this->em->flush();
        }
    }
}
