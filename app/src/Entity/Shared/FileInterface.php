<?php

declare(strict_types=1);

namespace App\Entity\Shared;

interface FileInterface
{
    /**
     * Generated filename
     */
    public function getFileName(): ?string;

    /**
     * Original filename
     */
    public function getOriginalFileName(): ?string;

    /**
     * Relative path from public directory to upload directory
     * ie: media/xxxx
     */
    public function getUploadDir(): string;
}
