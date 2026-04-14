<?php

namespace App\Repository;

use PDO;

class ArticleRepository
{
    private PDO $pdo;

    public function __construct(string $databasePath)
    {
        $this->pdo = new PDO('sqlite:' . $databasePath);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->migrate();
    }

    private function migrate(): void
    {
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS search_index (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                slug TEXT UNIQUE NOT NULL,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                description TEXT,
                tags TEXT DEFAULT \'\',
                date TEXT,
                embedding TEXT,
                created_at TEXT DEFAULT (datetime(\'now\')),
                updated_at TEXT DEFAULT (datetime(\'now\'))
            )
        ');

        $this->pdo->exec('
            CREATE VIRTUAL TABLE IF NOT EXISTS search_fts
            USING fts5(slug, title, content, content=search_index, content_rowid=id)
        ');
    }

    public function upsert(array $article): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO search_index (slug, title, content, description, tags, date, embedding)
            VALUES (:slug, :title, :content, :description, :tags, :date, :embedding)
            ON CONFLICT(slug) DO UPDATE SET
                title = excluded.title,
                content = excluded.content,
                description = excluded.description,
                tags = excluded.tags,
                date = excluded.date,
                embedding = excluded.embedding,
                updated_at = datetime(\'now\')
        ');

        $stmt->execute([
            ':slug'        => $article['slug'],
            ':title'       => $article['title'],
            ':content'     => $article['content'],
            ':description' => $article['description'] ?? null,
            ':tags'        => $article['tags'] ?? '',
            ':date'        => $article['date'] ?? null,
            ':embedding'   => $article['embedding'] ?? null,
        ]);

        // Sync FTS5 index using a prepared statement (safe against injection)
        $id = (int) $this->pdo->lastInsertId();
        if ($id > 0) {
            $fts = $this->pdo->prepare(
                'INSERT INTO search_fts(rowid, slug, title, content) VALUES (?, ?, ?, ?)'
            );
            $fts->execute([$id, $article['slug'], $article['title'], $article['content']]);
        }
    }

    public function search(string $query): array
    {
        $like = '%' . $query . '%';
        $stmt = $this->pdo->prepare('
            SELECT id, slug, title, description, tags, date
            FROM search_index
            WHERE title LIKE :q OR content LIKE :q
            ORDER BY updated_at DESC
            LIMIT 20
        ');
        $stmt->execute([':q' => $like]);

        return $stmt->fetchAll();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT id, slug, title, description, tags, date
            FROM search_index
            ORDER BY date DESC, updated_at DESC
        ');

        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM search_index WHERE slug = :slug
        ');
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function tableExists(): bool
    {
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='search_index'");
        return (bool) $stmt->fetch();
    }
}
