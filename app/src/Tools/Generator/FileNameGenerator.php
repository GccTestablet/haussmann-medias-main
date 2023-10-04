<?php

declare(strict_types=1);

namespace App\Tools\Generator;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class FileNameGenerator
{
    public static function generate(UploadedFile $file): string
    {
        return \sprintf('%s.%s', Uuid::v7(), $file->getClientOriginalExtension());
    }
}
