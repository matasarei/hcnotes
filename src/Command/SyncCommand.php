<?php

namespace App\Command;

use App\Repository\ArticleRepository;
use App\Service\ContentParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'app:sync',
    description: 'Index all Markdown content from /content into SQLite search_index',
)]
class SyncCommand extends Command
{
    public function __construct(
        private readonly ArticleRepository $repository,
        private readonly ContentParser $parser,
        private readonly string $contentDir,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('AICMF Content Sync');

        if (!is_dir($this->contentDir)) {
            $io->error("Content directory not found: {$this->contentDir}");
            return Command::FAILURE;
        }

        $finder = new Finder();
        $finder->files()->in($this->contentDir)->name('*.md')->sortByName();

        if (!$finder->hasResults()) {
            $io->warning('No Markdown files found in ' . $this->contentDir);
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($finder as $file) {
            $parsed = $this->parser->parse($file->getContents());
            $fm = $parsed['frontmatter'];

            $slug = $this->parser->filePathToSlug($file->getRealPath(), $this->contentDir);

            // Title priority: frontmatter > first H1 heading > filename
            if (isset($fm['title'])) {
                $title = $fm['title'];
            } elseif (preg_match('/^#\s+(.+)/m', $parsed['raw'], $h1)) {
                $title = trim($h1[1]);
            } else {
                $title = ucwords(str_replace('-', ' ', basename($file->getFilenameWithoutExtension())));
            }
            $tags = is_array($fm['tags'] ?? null) ? implode(',', $fm['tags']) : ($fm['tags'] ?? '');

            $this->repository->upsert([
                'slug'        => $slug,
                'title'       => $title,
                'content'     => $parsed['html'],
                'description' => $fm['description'] ?? null,
                'tags'        => $tags,
                'date'        => $this->validDate($fm['date'] ?? null, $file->getRelativePathname(), $io),
                'embedding'   => null,
            ]);

            $io->writeln("  Indexed: <info>{$slug}</info> ({$title})");
            $count++;
        }

        $io->success("Synced {$count} article(s).");
        return Command::SUCCESS;
    }

    /**
     * A front-matter date is kept only as a real YYYY-MM-DD day; anything else is
     * stored as null (the article falls back to its indexed-at time) and named in
     * a warning, so a typo cannot reach the page, the sitemap or the JSON-LD.
     */
    private function validDate(mixed $value, string $file, SymfonyStyle $io): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)
            && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $m)
            && checkdate((int) $m[2], (int) $m[3], (int) $m[1])
        ) {
            return $value;
        }

        $shown = is_string($value) ? $value : json_encode($value);
        $io->warning("Ignoring invalid date \"{$shown}\" in {$file}: expected YYYY-MM-DD.");

        return null;
    }
}
