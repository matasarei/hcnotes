<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Cache-busting for static assets.
 *
 * `asset_v('/assets/css/dedsec.css')` appends the file's modification time as a
 * `?v=` query string. The URL changes whenever the file changes, so browsers and
 * any CDN (Cloudflare) refetch on update instead of serving a stale copy.
 */
class AssetExtension extends AbstractExtension
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')] private readonly string $projectDir,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_v', $this->assetV(...)),
        ];
    }

    public function assetV(string $path): string
    {
        $file = $this->projectDir . '/public/' . ltrim($path, '/');
        $mtime = @filemtime($file);

        return $mtime ? $path . '?v=' . $mtime : $path;
    }
}
