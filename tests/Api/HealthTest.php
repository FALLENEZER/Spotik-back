<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthTest extends WebTestCase
{
    public function testHealthEndpointReturnsOk(): void
    {
        $client = static::createClient();

        // API Platform defaults to JSON-LD (Hydra). Request that explicitly.
        $client->request('GET', '/api/health', server: ['HTTP_ACCEPT' => 'application/ld+json']);

        self::assertResponseIsSuccessful();
        $ct = $client->getResponse()->headers->get('content-type');
        self::assertNotNull($ct, 'Content-Type header missing');
        self::assertStringContainsString('json', strtolower($ct));

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);
        // Collections in Hydra are under 'hydra:member', but other formats may be a plain array
        $items = $data['hydra:member'] ?? (array_is_list($data) ? $data : null);
        if ($items === null) {
            // Fallback: try common keys
            $items = $data['items'] ?? $data['member'] ?? [];
        }
        self::assertIsArray($items);
        self::assertNotEmpty($items);
        $first = $items[0] ?? null;
        self::assertIsArray($first);
        self::assertSame('ok', $first['status'] ?? null);
    }
}
