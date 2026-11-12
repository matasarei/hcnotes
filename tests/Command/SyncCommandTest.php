<?php

namespace App\Tests\Command;

use App\Command\SyncCommand;
use App\Repository\ArticleRepository;
use App\Service\ContentParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SyncCommandTest extends TestCase
{
    private string $dbPath;
    private string $contentDir;
    private ArticleRepository $repo;

    protected function setUp(): void
    {
        $this->dbPath = sys_get_temp_dir() . '/aicmf_sync_test_' . uniqid() . '.db';
        $this->contentDir = sys_get_temp_dir() . '/aicmf_content_' . uniqid();
        mkdir($this->contentDir . '/articles', 0777, true);

        $this->repo = new ArticleRepository($this->dbPath);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->dbPath)) {
            unlink($this->dbPath);
        }
        array_map('unlink', glob($this->contentDir . '/articles/*.md') ?: []);
        @rmdir($this->contentDir . '/articles');
        @rmdir($this->contentDir);
    }

    private function createCommand(): CommandTester
    {
        $command = new SyncCommand($this->repo, new ContentParser(), $this->contentDir);
        $application = new Application();
        $application->add($command);
        return new CommandTester($application->find('app:sync'));
    }

    public function testSyncIndexesMarkdownFiles(): void
    {
        file_put_contents($this->contentDir . '/articles/hello.md', <<<MD
---
title: Hello World
date: 2026-01-01
tags: [php, demo]
description: A test article
---
# Hello
This is a test.
MD);

        $tester = $this->createCommand();
        $tester->execute([]);

        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString('Synced 1 article', $tester->getDisplay());

        $article = $this->repo->findBySlug('articles-hello');
        $this->assertNotNull($article);
        $this->assertSame('Hello World', $article['title']);
        $this->assertSame('2026-01-01', $article['date']);
        $this->assertSame('php,demo', $article['tags']);
    }

    public function testSyncDropsMalformedDates(): void
    {
        $files = [
            'slashes.md'    => '10/11/2026',
            'impossible.md' => '2026-02-30',
            'list.md'       => '[2026-01-01]',
            'good.md'       => '2026-03-04',
            'empty.md'      => '',
        ];
        foreach ($files as $name => $date) {
            file_put_contents($this->contentDir . '/articles/' . $name, "---\ntitle: {$name}\ndate: {$date}\n---\nBody");
        }

        $tester = $this->createCommand();
        $tester->execute([]);

        $this->assertSame(0, $tester->getStatusCode(), 'A bad date is a warning, not a failed sync');
        $this->assertStringContainsString('Synced 5 article', $tester->getDisplay());

        $this->assertNull($this->repo->findBySlug('articles-slashes')['date']);
        $this->assertNull($this->repo->findBySlug('articles-impossible')['date']);
        $this->assertNull($this->repo->findBySlug('articles-list')['date']);
        $this->assertSame('2026-03-04', $this->repo->findBySlug('articles-good')['date'], 'Valid dates are stored unchanged');
        $this->assertNull($this->repo->findBySlug('articles-empty')['date'], 'An empty date is no date');

        // SymfonyStyle wraps long warnings, so compare with whitespace collapsed.
        $display = preg_replace('/\s+/', ' ', $tester->getDisplay());
        $this->assertStringContainsString('articles/slashes.md', $display);
        $this->assertStringContainsString('10/11/2026', $display);
        $this->assertStringContainsString('articles/impossible.md', $display);
        $this->assertStringContainsString('2026-02-30', $display);
        $this->assertStringContainsString('articles/list.md', $display);
        $this->assertStringNotContainsString('articles/good.md', $display, 'No warning for a valid date');
        $this->assertStringNotContainsString('articles/empty.md', $display, 'No warning for an empty date');
    }

    public function testSyncWithEmptyContentDir(): void
    {
        $tester = $this->createCommand();
        $tester->execute([]);

        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString('No Markdown files found', $tester->getDisplay());
    }

    public function testSyncWithMissingFrontmatter(): void
    {
        file_put_contents($this->contentDir . '/articles/bare.md', "# Just A Title\nNo frontmatter here.");

        $tester = $this->createCommand();
        $tester->execute([]);

        $this->assertSame(0, $tester->getStatusCode());
        $article = $this->repo->findBySlug('articles-bare');
        $this->assertNotNull($article);
        $this->assertSame('Just A Title', $article['title']);
    }

    public function testSyncUpdatesExistingArticle(): void
    {
        $path = $this->contentDir . '/articles/update.md';
        file_put_contents($path, "---\ntitle: Original\n---\nContent v1");
        $tester = $this->createCommand();
        $tester->execute([]);

        file_put_contents($path, "---\ntitle: Updated\n---\nContent v2");
        $tester->execute([]);

        $article = $this->repo->findBySlug('articles-update');
        $this->assertSame('Updated', $article['title']);
    }
}
