<?php

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Guards the theme palette. The colours live in four places that cannot share
 * a variable (CSS, the canvas script, the favicon and the theme-color meta), so
 * these tests keep them in sync and keep the retired cyan/pink "synthwave"
 * palette from creeping back in.
 */
class ThemePaletteTest extends TestCase
{
    private const CSS     = 'public/assets/css/dedsec.css';
    private const CANVAS  = 'public/assets/js/aleph.js';
    private const FAVICON = 'public/favicon.svg';
    private const BASE    = 'src/Themes/default/base.html.twig';

    public function testThemeColorMetaMatchesCssBackground(): void
    {
        $this->assertMatchesRegularExpression(
            '/<meta name="theme-color" content="' . preg_quote($this->cssBackground(), '/') . '">/i',
            $this->read(self::BASE),
            'The browser chrome colour must follow --bg'
        );
    }

    public function testCanvasBackgroundMatchesCssBackground(): void
    {
        $this->assertMatchesRegularExpression(
            "/var BG\\s*=\\s*'" . preg_quote($this->cssBackground(), '/') . "'/i",
            $this->read(self::CANVAS),
            'aleph.js clears the canvas with BG, which must follow --bg'
        );
    }

    public function testFaviconBackgroundMatchesCssBackground(): void
    {
        $this->assertSame(1, preg_match('/<rect\b[^>]*\bfill="(#[0-9a-f]{6})"/i', $this->read(self::FAVICON), $m));
        $this->assertSame(strtolower($this->cssBackground()), strtolower($m[1]), 'The favicon tile must follow --bg');
    }

    /**
     * @dataProvider retiredLiterals
     */
    public function testNoSynthwaveLiteralsRemain(string $file, string $pattern): void
    {
        foreach (preg_split('/\R/', $this->read($file)) as $i => $line) {
            // per-technology skill badges keep their brand colours on purpose
            if ($file === self::CSS && str_starts_with(ltrim($line), '.b-')) {
                continue;
            }
            $this->assertDoesNotMatchRegularExpression(
                $pattern,
                $line,
                sprintf('%s:%d still uses a retired palette colour', $file, $i + 1)
            );
        }
    }

    public static function retiredLiterals(): iterable
    {
        $literals = [
            'cyan'          => '#2de2e6',
            'pink'          => '#ff2e97',
            'bg'            => '#03050a',
            'bg-soft'       => '#070b14',
            'fg'            => '#c8f7f9',
            'fg-dim'        => '#5b7a82',
            'white'         => '#eafdff',
            'cyan rgb'      => '45,\s*226,\s*230',
            'pink rgb'      => '255,\s*46,\s*151',
            'blue-black 2'  => '\(2,\s*4,\s*8\b',
            'blue-black 3'  => '\(3,\s*5,\s*10\b',
            'blue-black 4'  => '\(4,\s*7,\s*13\b',
        ];

        foreach ([self::CSS, self::CANVAS, self::FAVICON, self::BASE] as $file) {
            foreach ($literals as $name => $regex) {
                yield basename($file) . ' / ' . $name => [$file, '/' . $regex . '/i'];
            }
        }
    }

    public function testNoColourNamedTokensOrClasses(): void
    {
        $css = $this->read(self::CSS);
        $this->assertDoesNotMatchRegularExpression('/--(cyan|pink)\b/', $css, 'Tokens are named by role, not by hue');
        $this->assertDoesNotMatchRegularExpression('/\.(k-cyan|k-pink|dot--c|dot--p|badge--c|badge--p)\b/', $css);

        $themes = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->path('src/Themes'), \FilesystemIterator::SKIP_DOTS));
        foreach ($themes as $file) {
            if ($file->getExtension() !== 'twig') {
                continue;
            }
            $this->assertDoesNotMatchRegularExpression(
                '/\b(k-cyan|k-pink|dot--c|dot--p|badge--c|badge--p)\b/',
                file_get_contents($file->getPathname()),
                $file->getPathname() . ' still uses a hue-named class'
            );
        }
    }

    private function cssBackground(): string
    {
        $this->assertSame(1, preg_match('/^\s*--bg:\s*(#[0-9a-f]{6})\s*;/mi', $this->read(self::CSS), $m), '--bg must be a 6-digit hex');

        return $m[1];
    }

    private function read(string $relative): string
    {
        $contents = file_get_contents($this->path($relative));
        $this->assertNotFalse($contents, $relative . ' is missing');

        return $contents;
    }

    private function path(string $relative): string
    {
        return dirname(__DIR__, 2) . '/' . $relative;
    }
}
