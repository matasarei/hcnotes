<?php

namespace App\Service;

use Parsedown;

class ContentParser
{
    private Parsedown $parsedown;

    public function __construct()
    {
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(false);
    }

    public function parse(string $fileContent): array
    {
        $frontmatter = [];
        $body = $fileContent;

        if (str_starts_with(ltrim($fileContent), '---')) {
            $parts = preg_split('/^---\s*$/m', ltrim($fileContent), 3);
            if (count($parts) >= 3) {
                $frontmatter = $this->parseYaml($parts[1]);
                $body = $parts[2];
            }
        }

        return [
            'frontmatter' => $frontmatter,
            'html'        => $this->parsedown->text(trim($body)),
            'raw'         => trim($body),
        ];
    }

    private function parseYaml(string $yaml): array
    {
        $result = [];
        foreach (explode("\n", trim($yaml)) as $line) {
            if (str_contains($line, ':')) {
                [$key, $value] = explode(':', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Handle YAML arrays: [tag1, tag2] or quoted strings
                if (str_starts_with($value, '[') && str_ends_with($value, ']')) {
                    $items = explode(',', trim($value, '[]'));
                    $value = array_map(fn($v) => trim($v, ' "\''), $items);
                } else {
                    $value = trim($value, '"\'');
                }

                if ($key !== '') {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

    public function slugify(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', trim($text));
        return trim($text, '-');
    }

    public function filePathToSlug(string $filePath, string $contentDir): string
    {
        $relative = str_replace($contentDir . '/', '', $filePath);
        $relative = preg_replace('/\.md$/', '', $relative);
        return str_replace(['/', '\\'], '-', $relative);
    }
}
