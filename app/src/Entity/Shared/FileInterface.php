<?php

declare(strict_types=1);

namespace App\Entity\Shared;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function getFile(): ?UploadedFile;

    public function setFile(UploadedFile $file): static;

    /**
     * Relative path from public directory to upload directory
     * ie: media/xxxx
     */
    public function getUploadDir(): string;
}
