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
        $this->assertSame('Software Engineer', $data['jobTitle'] ?? null, 'One role title everywhere');
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
        // A wide card where og:image is the 1200x630 social card; About shows the square avatar.
        $this->assertSame(
            $path === '/about' ? 'summary' : 'summary_large_image',
            $crawler->filter('meta[name="twitter:card"]')->attr('content')
        );

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

    /**
     * The role is "software engineer" everywhere; the older web/backend developer
     * wordings must not come back on any page a search engine reads.
     *
     * @dataProvider pagePaths
     */
    public function testPagesUseOneRoleTitle(string $path): void
    {
        $client = static::createClient();
        $client->request('GET', $path);

        $this->assertResponseIsSuccessful();
        $html = $client->getResponse()->getContent();

        $this->assertDoesNotMatchRegularExpression('/web\.dev|web[ -]developer|back-?end developer/i', $html);
        $this->assertStringContainsStringIgnoringCase('software engineer', $html);
    }

    public function testAboutPageMentionsTechLeadOnlyInTheBio(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
        $this->assertStringNotContainsString('Tech Lead', $crawler->filter('title')->text());
        $this->assertStringNotContainsString('Tech Lead', $crawler->filter('meta[name="description"]')->attr('content'));
        $this->assertStringNotContainsString('Tech Lead', $crawler->filter('script[type="application/ld+json"]')->text());
        $this->assertStringContainsString('Tech Lead', $crawler->filter('.about__bio')->text(), 'The bio still says what the job is today');
    }

    public function testHomePageHasSeoTitleHeadingAndDescription(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSame(1, $crawler->filter('h1')->count(), 'The home page needs exactly one <h1>');
        $this->assertStringContainsStringIgnoringCase('software engineer', $crawler->filter('h1')->text());

        $title = $crawler->filter('title')->text();
        $this->assertStringContainsString('Yevhen Matasar', $title);
        $this->assertStringContainsStringIgnoringCase('software engineer', $title);

        $description = $crawler->filter('meta[name="description"]')->attr('content');
        $this->assertGreaterThanOrEqual(80, mb_strlen($description));
        $this->assertLessThanOrEqual(170, mb_strlen($description), 'Meta description must fit a search snippet');
    }

    public function testArticlePageExposesBlogPostingMetadata(): void
    {
        $client = static::createClient();

        /** @var \App\Repository\ArticleRepository $repo */
        $repo = static::getContainer()->get(\App\Repository\ArticleRepository::class);
        $title = 'Breaking </script><script>alert(1)</script> Out';
        $repo->upsert([
            'slug'        => 'blogposting-test-article',
            'title'       => $title,
            'content'     => '<p>Body</p>',
            'description' => 'A test post about structured data.',
            'tags'        => 'test',
            'date'        => '2026-03-04',
            'embedding'   => null,
        ]);

        $crawler = $client->request('GET', '/article/blogposting-test-article');

        $this->assertResponseIsSuccessful();
        $this->assertSame('article', $crawler->filter('meta[property="og:type"]')->attr('content'));
        $this->assertSame('2026-03-04', $crawler->filter('meta[property="article:published_time"]')->attr('content'));

        $html = $client->getResponse()->getContent();
        $this->assertStringNotContainsString('<script>alert(1)', $html, 'A title must never open a script element');

        $jsonLd = $crawler->filter('script[type="application/ld+json"]');
        $this->assertSame(1, $jsonLd->count(), 'Article pages must expose one JSON-LD block');

        $data = json_decode($jsonLd->text(), true, 512, JSON_THROW_ON_ERROR);
        $baseUrl = static::getContainer()->getParameter('site_base_url');
        $this->assertSame('BlogPosting', $data['@type'] ?? null);
        $this->assertSame($title, $data['headline'] ?? null);
        $this->assertSame('A test post about structured data.', $data['description'] ?? null);
        $this->assertSame('2026-03-04', $data['datePublished'] ?? null);
        $this->assertSame($baseUrl . '/article/blogposting-test-article', $data['url'] ?? null);
        $this->assertSame('Person', $data['author']['@type'] ?? null);
        $this->assertSame('Yevhen Matasar', $data['author']['name'] ?? null);
        $this->assertSame($baseUrl . '/about', $data['author']['url'] ?? null);
    }

    public function testHomePageExposesWebSiteJsonLd(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $jsonLd = $crawler->filter('script[type="application/ld+json"]');
        $this->assertSame(1, $jsonLd->count(), 'The home page must expose one JSON-LD block');

        $data = json_decode($jsonLd->text(), true, 512, JSON_THROW_ON_ERROR);
        $baseUrl = static::getContainer()->getParameter('site_base_url');
        $this->assertSame('https://schema.org', $data['@context'] ?? null);
        $this->assertSame('WebSite', $data['@type'] ?? null);
        $this->assertSame('HCNOTES', $data['name'] ?? null);
        $this->assertSame($baseUrl . '/', $data['url'] ?? null);
        $this->assertSame(
            $crawler->filter('meta[name="description"]')->attr('content'),
            $data['description'] ?? null,
            'The structured description must match the meta description'
        );
        $this->assertSame('Person', $data['author']['@type'] ?? null);
        $this->assertSame('Yevhen Matasar', $data['author']['name'] ?? null);
        $this->assertSame($baseUrl . '/about', $data['author']['url'] ?? null);
    }

    public function testDefaultSocialImageIsTheCard(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $baseUrl = static::getContainer()->getParameter('site_base_url');
        $file = static::getContainer()->getParameter('kernel.project_dir') . '/public/shared/img/og-card.png';
        $this->assertFileExists($file);

        // Versioned like every other asset, so link-preview caches refetch a re-rendered card.
        $this->assertSame(
            $baseUrl . '/shared/img/og-card.png?v=' . filemtime($file),
            $crawler->filter('meta[property="og:image"]')->attr('content')
        );

        $size = getimagesize($file);
        $this->assertNotFalse($size, 'og-card.png must be a readable image');
        $this->assertSame([1200, 630, IMAGETYPE_PNG], [$size[0], $size[1], $size[2]], 'The social card is a 1200x630 PNG');

        // Declared size and alt let crawlers lay out the preview without fetching the image first.
        $this->assertSame((string) $size[0], $crawler->filter('meta[property="og:image:width"]')->attr('content'));
        $this->assertSame((string) $size[1], $crawler->filter('meta[property="og:image:height"]')->attr('content'));
        $this->assertNotEmpty(trim((string) $crawler->filter('meta[property="og:image:alt"]')->attr('content')));
    }

    public function testAboutPageDoesNotDescribeTheCardForItsAvatar(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
        $this->assertSame(
            0,
            $crawler->filter('meta[property^="og:image:"]')->count(),
            'The card size and alt must not be claimed for the square avatar'
        );
    }

    public static function pagePaths(): iterable
    {
        yield 'home' => ['/'];
        yield 'about' => ['/about'];
    }
}
