<?php

namespace App\Tests\Integration;

use App\Repository\ArticleRepository;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private ArticleRepository $repo;
    private string $dbPath;

    protected function setUp(): void
    {
        $this->dbPath = sys_get_temp_dir() . '/aicmf_test_' . uniqid() . '.db';
        $this->repo = new ArticleRepository($this->dbPath);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->dbPath)) {
            unlink($this->dbPath);
        }
    }

    public function testTableIsCreatedOnConnection(): void
    {
        $this->assertTrue($this->repo->tableExists(), 'search_index table must exist after construction');
    }

    public function testUpsertInsertsNewArticle(): void
    {
        $this->repo->upsert([
            'slug'        => 'test-article',
            'title'       => 'Test Article',
            'content'     => '<p>Hello world</p>',
            'description' => 'A test',
            'tags'        => 'php,test',
            'date'        => '2026-01-01',
            'embedding'   => null,
        ]);

        $article = $this->repo->findBySlug('test-article');

        $this->assertNotNull($article);
        $this->assertSame('Test Article', $article['title']);
        $this->assertSame('php,test', $article['tags']);
    }

    public function testUpsertUpdatesExistingArticle(): void
    {
        $this->repo->upsert([
            'slug'    => 'update-me',
            'title'   => 'Original Title',
            'content' => '<p>Original</p>',
            'tags'    => '',
            'date'    => null,
            'embedding' => null,
        ]);

        $this->repo->upsert([
            'slug'    => 'update-me',
            'title'   => 'Updated Title',
            'content' => '<p>Updated</p>',
            'tags'    => 'updated',
            'date'    => null,
            'embedding' => null,
        ]);

        $article = $this->repo->findBySlug('update-me');
        $this->assertSame('Updated Title', $article['title']);
    }

    public function testSearchReturnsMatchingArticles(): void
    {
        $this->repo->upsert([
            'slug'    => 'php-tips',
            'title'   => 'PHP Tips and Tricks',
            'content' => '<p>Learn PHP effectively</p>',
            'tags'    => 'php',
            'date'    => null,
            'embedding' => null,
        ]);

        $this->repo->upsert([
            'slug'    => 'golang-intro',
            'title'   => 'Introduction to Go',
            'content' => '<p>Learn Go effectively</p>',
            'tags'    => 'go',
            'date'    => null,
            'embedding' => null,
        ]);

        $results = $this->repo->search('PHP');
        $this->assertCount(1, $results);
        $this->assertSame('php-tips', $results[0]['slug']);
    }

    public function testFindAllReturnsAllArticles(): void
    {
        $this->repo->upsert(['slug' => 'a', 'title' => 'A', 'content' => 'c', 'tags' => '', 'date' => null, 'embedding' => null]);
        $this->repo->upsert(['slug' => 'b', 'title' => 'B', 'content' => 'c', 'tags' => '', 'date' => null, 'embedding' => null]);

        $all = $this->repo->findAll();
        $this->assertCount(2, $all);
    }
}
