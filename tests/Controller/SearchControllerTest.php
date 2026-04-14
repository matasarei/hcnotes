<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testSearchReturnsBadRequestWithoutQuery(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/search');

        $this->assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testSearchReturnsJsonArrayWithQuery(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/search?q=hello');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }

    public function testSearchResultStructure(): void
    {
        // Seed one record directly into the test DB via the container
        $client = static::createClient();

        /** @var \App\Repository\ArticleRepository $repo */
        $repo = static::getContainer()->get(\App\Repository\ArticleRepository::class);
        $repo->upsert([
            'slug'        => 'test-structure',
            'title'       => 'Structure Test',
            'content'     => '<p>Checking result keys</p>',
            'description' => 'Test',
            'tags'        => 'test',
            'date'        => '2026-01-01',
            'embedding'   => null,
        ]);

        $client->request('GET', '/api/search?q=Structure');

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($data);
        $first = $data[0];
        $this->assertArrayHasKey('slug', $first);
        $this->assertArrayHasKey('title', $first);
        $this->assertArrayHasKey('tags', $first);
        $this->assertIsArray($first['tags']);
    }
}
