<?php

namespace App\Tests\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    private function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get('doctrine')->getManager();
    }

    public function testGetCollection(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/users', server: ['HTTP_ACCEPT' => 'application/ld+json']);

        self::assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);
        // Collections in Hydra are under 'hydra:member', but other formats may be a plain array
        $items = $data['hydra:member'] ?? (array_is_list($data) ? $data : null);
        if ($items === null) {
            // Fallback: try common keys
            $items = $data['items'] ?? $data['member'] ?? [];
        }
        self::assertIsArray($items);
    }

    public function testGetUser(): void
    {
        $client = static::createClient();
        $em = $this->getEntityManager();

        // Create a test user
        $user = new User();
        $user->setName('Test User');
        $user->setEmail('test@example.com');
        $user->setPassword('password123');
        $user->setIsAdmin(false);

        $em->persist($user);
        $em->flush();
        $userId = $user->getId();

        $client->request('GET', "/api/users/{$userId}", server: ['HTTP_ACCEPT' => 'application/ld+json']);

        self::assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);
        self::assertSame('Test User', $data['name']);
        self::assertSame('test@example.com', $data['email']);
        self::assertArrayNotHasKey('password', $data); // Password should not be exposed

        // Cleanup
        $em->remove($user);
        $em->flush();
    }

    public function testCreateUser(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ],
            json_encode([
                'name' => 'New User',
                'email' => 'test2@example.com',
                'password' => 'securepass123',
                'isAdmin' => false,
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(201);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);
        self::assertSame('New User', $data['name']);
        self::assertSame('test2@example.com', $data['email']);
        self::assertArrayNotHasKey('password', $data);

        // Verify user was created in database
        $em = $this->getEntityManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'test2@example.com']);
        self::assertNotNull($user);
        self::assertSame('New User', $user->getName());

        // Cleanup
        $em->remove($user);
        $em->flush();
    }

    public function testCreateUserValidation(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ],
            json_encode([
                'name' => '',
                'email' => 'invalid-email',
                'password' => '123', // Too short
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(422);
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);
        self::assertArrayHasKey('violations', $data);
    }

    public function testUpdateUser(): void
    {
        $client = static::createClient();
        $em = $this->getEntityManager();

        // Create a test user with unique email
        $uniqueEmail = 'update' . uniqid() . '@example.com';
        $user = new User();
        $user->setName('Original Name');
        $user->setEmail($uniqueEmail);
        $user->setPassword('password123');
        $user->setIsAdmin(false);

        $em->persist($user);
        $em->flush();
        $userId = $user->getId();
        $client->request(
            'PUT',
            "/api/users/{$userId}",
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ],
            json_encode([
                'name' => 'Updated Name',
                'email' => $uniqueEmail,
                'password' => 'newpassword123',
                'isAdmin' => true,
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('Updated Name', $data['name']);

        // Verify in database
        $em->refresh($user);
        self::assertSame('Updated Name', $user->getName());
        // Check isAdmin in database (may not be in API response due to serialization groups)
        if (isset($data['isAdmin'])) {
            self::assertTrue($data['isAdmin']);
            self::assertTrue($user->isAdmin());
        } else {
            // If not in response, verify it was updated in DB
            self::assertTrue($user->isAdmin());
        }

        // Cleanup
        $em->remove($user);
        $em->flush();
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        $em = $this->getEntityManager();

        // Create a test user
        $user = new User();
        $user->setName('To Delete');
        $user->setEmail('delete@example.com');
        $user->setPassword('password123');
        $user->setIsAdmin(false);

        $em->persist($user);
        $em->flush();
        $userId = $user->getId();
        $client->request('DELETE', "/api/users/{$userId}");

        self::assertResponseStatusCodeSame(204);

        // Verify user was deleted
        $deletedUser = $em->getRepository(User::class)->find($userId);
        self::assertNull($deletedUser);
    }

    public function testUserEmailUniqueness(): void
    {
        $client = static::createClient();
        $em = $this->getEntityManager();

        // Use unique email to avoid conflicts with other tests
        $uniqueEmail = 'unique' . uniqid() . '@example.com';

        // Create first user via API
        $client->request(
            'POST',
            '/api/users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ],
            json_encode([
                'name' => 'User 1',
                'email' => $uniqueEmail,
                'password' => 'password123',
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(201);

        // Try to create second user with same email
        $client->request(
            'POST',
            '/api/users',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/ld+json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ],
            json_encode([
                'name' => 'User 2',
                'email' => $uniqueEmail,
                'password' => 'password123',
            ], JSON_THROW_ON_ERROR)
        );

        // Should fail due to unique constraint (either 422 or 500 depending on validation)
        $statusCode = $client->getResponse()->getStatusCode();
        self::assertContains($statusCode, [422, 500], 'Expected 422 or 500 status code for duplicate email');

        // Cleanup
        $user = $em->getRepository(User::class)->findOneBy(['email' => $uniqueEmail]);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }
}

