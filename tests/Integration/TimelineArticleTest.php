<?php

namespace App\Tests\Integration;

use App\Service\ContentParser;
use PHPUnit\Framework\TestCase;

/**
 * Guards "From CMS to Harness", the one article whose body is mostly a raw
 * HTML infographic. Parsedown ends a raw HTML block at the first blank line
 * and renders whatever follows as Markdown, so a stray empty line inside the
 * timeline turns half of it into escaped text or a code block. These tests
 * catch that, and keep the file clean of the characters the content rules ban.
 */
class TimelineArticleTest extends TestCase
{
    private const ARTICLE = 'content/articles/from-cms-to-harness.md';
    private const ERAS = 7;

    public function testTimelineSurvivesMarkdownParsingIntact(): void
    {
        $html = $this->html();

        $this->assertSame(1, substr_count($html, '<ol class="timeline">'), 'Exactly one timeline list');
        $this->assertSame(self::ERAS, preg_match_all('/<li class="era[" ]/', $html), 'One card per era');
        $this->assertSame(1, substr_count($html, '<div class="timeline-bar"'), 'Exactly one overview bar');
        $this->assertSame(self::ERAS, preg_match_all('/<span class="timeline-bar__seg[" ]/', $html), 'One bar segment per era');

        $this->assertDoesNotMatchRegularExpression('/&lt;\/?[a-z]/i', $html, 'No HTML may be escaped into text');
        $this->assertStringNotContainsString('<pre><code>', $html, 'No HTML may fall into a code block');
        $this->assertDoesNotMatchRegularExpression('/<p>\s*<(span|li|div|ul|h2)\b/', $html, 'No HTML may be wrapped into a paragraph');
    }

    public function testTimelineHtmlHasNoBlankLines(): void
    {
        $this->assertSame(
            1,
            preg_match('/<div class="timeline-bar".*?<\/ol>/s', $this->raw(), $block),
            'The overview bar and the timeline form one HTML block'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\n[ \t]*\n/',
            $block[0],
            'A blank line ends a raw HTML block in Parsedown and turns the rest into Markdown'
        );
    }

    public function testEveryEraHasYearsAFeltParagraphAndCheckedFacts(): void
    {
        preg_match_all('/<li class="era(?: era--ai)?">(.*?)<\/li>\s*(?=<li class="era[" ]|<\/ol>)/s', $this->html(), $eras);

        $this->assertCount(self::ERAS, $eras[1]);
        foreach ($eras[1] as $i => $era) {
            $n = $i + 1;
            $this->assertMatchesRegularExpression('/<h2 class="era__years">\s*\d{4}/', $era, "Era $n needs a years heading");
            $this->assertStringContainsString('<p class="era__felt">', $era, "Era $n needs a felt paragraph");
            $this->assertGreaterThanOrEqual(2, substr_count($era, '<li class="era__fact">'), "Era $n needs at least two facts");
        }
    }

    public function testArticleUsesOnlyPlainCharacters(): void
    {
        $raw = $this->raw();

        $this->assertDoesNotMatchRegularExpression(
            '/[\x{200B}-\x{200D}\x{FEFF}\x{00AD}\x{2060}\x{200E}\x{200F}]/u',
            $raw,
            'No invisible characters'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/[\x{2018}\x{2019}\x{201C}\x{201D}\x{2026}\x{00A0}\x{2011}]/u',
            $raw,
            'Plain quotes, hyphens and ... only'
        );
        $this->assertDoesNotMatchRegularExpression('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $raw, 'No emoji');
    }

    public function testCardsLinkToTheAuthorsOwnArticles(): void
    {
        $this->assertGreaterThanOrEqual(
            5,
            preg_match_all('/<p class="era__more">.*?<a href="\/article\/articles-[a-z0-9-]+">/', $this->raw()),
            'Cards point readers to the articles that tell that part of the story'
        );
    }

    public function testInternalArticleLinksResolve(): void
    {
        preg_match_all('#/article/articles-([a-z0-9-]+)#', $this->raw(), $m);

        $this->assertNotEmpty($m[1]);
        foreach (array_unique($m[1]) as $name) {
            $this->assertFileExists(
                dirname(__DIR__, 2) . '/content/articles/' . $name . '.md',
                "Link to /article/articles-$name has no article behind it"
            );
        }
    }

    public function testFrontmatterIsComplete(): void
    {
        $front = (new ContentParser())->parse($this->raw())['frontmatter'];

        $this->assertStringStartsWith('From CMS to Harness', $front['title'] ?? '');
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $front['date'] ?? '');
        $this->assertNotEmpty($front['description'] ?? '');
        $this->assertIsArray($front['tags'] ?? null);
    }

    private function html(): string
    {
        return (new ContentParser())->parse($this->raw())['html'];
    }

    private function raw(): string
    {
        $path = dirname(__DIR__, 2) . '/' . self::ARTICLE;
        $this->assertFileExists($path);

        return (string) file_get_contents($path);
    }
}
