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

    public function testAboutPageLinksToTheBetterTimesArticle(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();

        $link = $crawler->filter('.about__bio a[href="/article/articles-better-times-are-ahead-bring-boots"]');
        $this->assertSame(
            1,
            $link->count(),
            'The "better times are ahead" line must link to the article that explains it'
        );
    }

    public function testAboutPageHasPersonFocusedSeoMetadata(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();

        $title = $crawler->filter('title')->text();
        $this->assertStringContainsString('Yevhen Matasar', $title, 'The <title> must lead with the author name');

        $description = $crawler->filter('meta[name="description"]')->attr('content');
        $this->assertStringContainsString('Yevhen Matasar', $description);
        $this->assertGreaterThanOrEqual(80, strlen($description));
        $this->assertLessThanOrEqual(170, strlen($description), 'Meta description must fit a search snippet');
        $this->assertStringNotContainsStringIgnoringCase('fintech', $description, 'Do not advertise the employer sector');

        $this->assertSame(
            1,
            $crawler->filter('a[href="https://github.com/matasarei"][rel~="me"]')->count(),
            'The GitHub profile link must carry rel="me"'
        );

        $jsonLd = $crawler->filter('script[type="application/ld+json"]');
        $this->assertSame(1, $jsonLd->count(), 'The About page must expose a JSON-LD block');

        $data = json_decode($jsonLd->text(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Person', $data['@type'] ?? null);
        $this->assertSame('Yevhen Matasar', $data['name'] ?? null);
        $this->assertContains('https://github.com/matasarei', $data['sameAs'] ?? []);
        $this->assertArrayNotHasKey('worksFor', $data, 'Do not publish the employer in structured data');
    }

    /**
     * @dataProvider pagePaths
     */
    public function testPagesExposeCanonicalAndOpenGraphTags(string $path): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $path);

        $this->assertResponseIsSuccessful();

        $baseUrl = static::getContainer()->getParameter('site_base_url');
        $expectedUrl = $baseUrl . $path;

        $this->assertSame($expectedUrl, $crawler->filter('link[rel="canonical"]')->attr('href'));
        $this->assertSame($expectedUrl, $crawler->filter('meta[property="og:url"]')->attr('content'));

        $title = $crawler->filter('title')->text();
        $this->assertSame($title, $crawler->filter('meta[property="og:title"]')->attr('content'));

        $description = $crawler->filter('meta[name="description"]')->attr('content');
        $this->assertSame($description, $crawler->filter('meta[property="og:description"]')->attr('content'));

        $this->assertMatchesRegularExpression(
            '#^https?://#',
            $crawler->filter('meta[property="og:image"]')->attr('content'),
            'og:image must be an absolute URL'
        );
        $this->assertSame('summary', $crawler->filter('meta[name="twitter:card"]')->attr('content'));

        $generator = $crawler->filter('meta[name="generator"]');
        $this->assertSame(1, $generator->count(), 'Every page must declare AICMF as its generator');
        $this->assertStringStartsWith('AICMF', $generator->attr('content'));
        $this->assertStringContainsString('https://github.com/matasarei/aicmf', $generator->attr('content'));
    }

    public function testArticlePageUsesTheCalmBackgroundScene(): void
    {
        $client = static::createClient();

        /** @var \App\Repository\ArticleRepository $repo */
        $repo = static::getContainer()->get(\App\Repository\ArticleRepository::class);
        $repo->upsert([
            'slug'        => 'scene-test-article',
            'title'       => 'Scene Test',
            'content'     => '<p>Scene body</p>',
            'description' => 'Test',
            'tags'        => 'test',
            'date'        => '2026-02-03',
            'embedding'   => null,
        ]);

        $crawler = $client->request('GET', '/article/scene-test-article');

        $this->assertResponseIsSuccessful();
        $this->assertSame(
            1,
            $crawler->filter('canvas#aleph-bg[data-scene="calm"]')->count(),
            'Article pages must ask the background for the calm scene'
        );
        $this->assertSame(
            1,
            $crawler->filter('body.scene-calm')->count(),
            'Article pages must carry the scene class so CSS can tone down the overlays'
        );
    }

    /**
     * @dataProvider pagePaths
     */
    public function testNonArticlePagesUseTheFullBackgroundScene(string $path): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $path);

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('canvas#aleph-bg[data-scene="full"]')->count());
        $this->assertSame(1, $crawler->filter('body.scene-full')->count());
    }

    public static function pagePaths(): iterable
    {
        yield 'home' => ['/'];
        yield 'about' => ['/about'];
    }
}
