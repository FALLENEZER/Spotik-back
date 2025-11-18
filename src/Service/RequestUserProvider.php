<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestUserProvider
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function resolve(Request $request): User
    {
        if ($existing = $this->resolveOptional($request)) {
            return $existing;
        }

        $email = $request->headers->get('X-User-Email');
        if ($email) {
            $name = $request->headers->get('X-User-Name') ?? 'Guest '.substr($email, 0, 4);

            return $this->createUser($name, $email);
        }

        return $this->getOrCreateDemoUser();
    }

    public function resolveOptional(Request $request): ?User
    {
        $userId = $request->headers->get('X-User-Id');
        if ($userId !== null) {
            $found = $this->userRepository->find((int) $userId);
            if ($found instanceof User) {
                return $found;
            }
        }

        $email = $request->headers->get('X-User-Email');
        if ($email) {
            $user = $this->userRepository->findOneBy(['email' => $email]);
            if ($user instanceof User) {
                return $user;
            }
        }

        return null;
    }

    private function getOrCreateDemoUser(): User
    {
        $email = 'demo@spotik.local';
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user instanceof User) {
            return $user;
        }

        return $this->createUser('Spotik Demo', $email);
    }

    private function createUser(string $name, string $email): User
    {
        $user = new User();
        $user
            ->setName($name)
            ->setEmail($email)
            ->setPassword(bin2hex(random_bytes(8)))
            ->setIsAdmin(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}

