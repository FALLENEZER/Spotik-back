<?php

namespace App\Repository;

use App\Entity\Track;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Track>
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(private EntityManagerInterface $em,ManagerRegistry $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function store(Track $track, $isFlush = true): Track
    {
        $this->em->persist($track);

        if ($isFlush) {
            $this->em->flush();
        }

        return $track;
    }

    public function search(?string $name, ?string $artist)
    {
        $qb = $this->createQueryBuilder('t');

        if ($name) {
            $qb->andWhere('LOWER(t.name) LIKE :name')
                ->setParameter('name', '%' . mb_strtolower($name) . '%');
        }

        if ($artist) {
            $qb->andWhere('LOWER(t.artist) LIKE :artist')
                ->setParameter('artist', '%' . mb_strtolower($artist) . '%');
        }

        $qb->orderBy('t.name', 'ASC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }

    public function delete(Track $track, $isFlush = true): void
    {
        $this->em->remove($track);

        if ($isFlush) {
            $this->em->flush();
        }
    }
    //    /**
    //     * @return Track[] Returns an array of Track objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Track
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
