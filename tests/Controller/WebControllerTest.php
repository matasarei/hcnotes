<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebControllerTest extends WebTestCase
{
    public function testAboutPageLinksBackToTheArticles(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();

        $link = $crawler->filter('.about .btn-link[href="/"]');
        $this->assertGreaterThan(
            0,
            $link->count(),
            'The About page must link back to the article index'
        );
        $this->assertStringContainsStringIgnoringCase('read', $link->first()->text());
    }
}
