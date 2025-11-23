<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocsTest extends WebTestCase
{
    public function testDocsPageShowsHealth(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/docs');

        self::assertResponseIsSuccessful();

        $html = $client->getResponse()->getContent();
        self::assertIsString($html);
        self::assertStringContainsStringIgnoringCase('Health', $html, 'Docs page should mention the Health resource');
    }
}
