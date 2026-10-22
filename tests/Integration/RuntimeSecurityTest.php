<?php

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Guards the PHP runtime the site ships in (docker/php). Runs inside the php
 * container, so it checks the real image and the real docker/php/php.ini.
 */
class RuntimeSecurityTest extends TestCase
{
    /**
     * PHP2Shell (CVE-2026-109618, CVE-2026-109619) is fixed in 8.4.27 and
     * 8.5.12. The site targets 8.4+ (composer.json), so older branches fail.
     */
    public function testPhpIsPatchedAgainstPhp2Shell(): void
    {
        $minimum = match (PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION) {
            '8.4' => '8.4.27',
            '8.5' => '8.5.12',
            default => null,
        };

        $this->assertNotNull($minimum, 'Unsupported PHP branch ' . PHP_VERSION);
        $this->assertTrue(
            version_compare(PHP_VERSION, $minimum, '>='),
            sprintf('PHP %s is vulnerable to PHP2Shell, need >= %s', PHP_VERSION, $minimum),
        );
    }

    public function testSessionUploadProgressIsDisabled(): void
    {
        // The demonstrated CVE-2026-109619 chain goes through upload progress.
        $this->assertFalse((bool) ini_get('session.upload_progress.enabled'));
    }

    public function testFileUploadsAreDisabled(): void
    {
        // The site accepts no uploads; multipart parsing is the CVE-2026-109618 path.
        $this->assertFalse((bool) ini_get('file_uploads'));
    }

    public function testRequestBodiesAreSmall(): void
    {
        $this->assertLessThanOrEqual(1024 * 1024, $this->bytes((string) ini_get('post_max_size')));
    }

    public function testPhpVersionIsNotAdvertised(): void
    {
        $this->assertFalse((bool) ini_get('expose_php'));
    }

    private function bytes(string $value): int
    {
        $number = (int) $value;

        return match (strtoupper(substr(trim($value), -1))) {
            'G' => $number * 1024 ** 3,
            'M' => $number * 1024 ** 2,
            'K' => $number * 1024,
            default => $number,
        };
    }
}
