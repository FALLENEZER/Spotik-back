<?php

namespace App\Repository;

use App\DTO\Input\User\UserUpdateDTO;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(private EntityManagerInterface $em, ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function store(User $user, $isFlush = true): User
    {
        $this->em->persist($user);

        if ($isFlush) {
            $this->em->flush();
        }

        return $user;
    }

    public function update(User $user, $isFlush = true): User
    {
        $this->em->persist($user);

        if ($isFlush) {
            $this->em->flush();
        }

        return $user;
    }
}
