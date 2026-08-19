<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SitemapControllerTest extends WebTestCase
{
    public function testSitemapIsServedAsXml(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        $this->assertResponseIsSuccessful();
        $this->assertStringStartsWith(
            'application/xml',
            $client->getResponse()->headers->get('Content-Type')
        );
    }

    public function testSitemapIsWellFormedAndContainsStaticPages(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        $xml = simplexml_load_string($client->getResponse()->getContent());
        $this->assertNotFalse($xml, 'Sitemap must be well-formed XML');

        $baseUrl = static::getContainer()->getParameter('site_base_url');
        $locs = [];
        foreach ($xml->url as $url) {
            $locs[] = (string) $url->loc;
        }

        $this->assertContains($baseUrl . '/', $locs);
        $this->assertContains($baseUrl . '/about', $locs);

        foreach ($locs as $loc) {
            $this->assertStringStartsWith($baseUrl, $loc, 'All URLs must use site_base_url');
        }
    }

    public function testSitemapListsIndexedArticlesWithLastmod(): void
    {
        $client = static::createClient();

        /** @var \App\Repository\ArticleRepository $repo */
        $repo = static::getContainer()->get(\App\Repository\ArticleRepository::class);
        $repo->upsert([
            'slug'        => 'sitemap-test-article',
            'title'       => 'Sitemap Test',
            'content'     => '<p>Sitemap body</p>',
            'description' => 'Test',
            'tags'        => 'test',
            'date'        => '2026-02-03',
            'embedding'   => null,
        ]);

        $client->request('GET', '/sitemap.xml');
        $this->assertResponseIsSuccessful();

        $xml = simplexml_load_string($client->getResponse()->getContent());
        $this->assertNotFalse($xml);

        $baseUrl = static::getContainer()->getParameter('site_base_url');
        $found = null;
        foreach ($xml->url as $url) {
            if ((string) $url->loc === $baseUrl . '/article/sitemap-test-article') {
                $found = $url;
            }
        }

        $this->assertNotNull($found, 'Indexed article must appear in the sitemap');
        $this->assertSame('2026-02-03', (string) $found->lastmod);
    }

    public function testSitemapDoesNotListSearchApi(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        $this->assertStringNotContainsString(
            '/api/search',
            $client->getResponse()->getContent()
        );
    }
}
