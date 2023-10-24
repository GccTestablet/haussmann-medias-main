<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Generator;

use App\Tests\Shared\AbstractTestCase;
use App\Tools\Generator\FileNameGenerator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileNameGeneratorTest extends AbstractTestCase
{
    public function testGenerate(): void
    {
        $filePath = $this->getFile('tests/Fixtures/images/logo.png');
        $file = new UploadedFile(
            $filePath,
            'logo.png',
            'image/png',
            null,
            true
        );

        $fileName = FileNameGenerator::generate($file);

        $this->assertIsString($fileName);
        $this->assertSame(36 + 4, \strlen($fileName));
        $this->assertSame('png', \pathinfo($fileName, \PATHINFO_EXTENSION));
    }
}
