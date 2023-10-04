<?php

declare(strict_types=1);

namespace App\Tools\Manager;

use App\Entity\Shared\FileInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UploadFileManager
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {}

    public function path(string $directory, string $filename): string
    {
        return \sprintf(
            '%s/public/%s/%s',
            $this->parameterBag->get('kernel.project_dir'),
            $directory,
            $filename
        );
    }

    public function upload(UploadedFile $file, string $directory, string $filename): void
    {
        $file->move($directory, $filename);
    }

    public function remove(FileInterface $file): void
    {
        $fileSystem = new Filesystem();
        $path = $this->path($file->getUploadDir(), $file->getFileName());

        if (!$fileSystem->exists($path)) {
            return;
        }

        $fileSystem->remove($path);
    }

    public function download(FileInterface $file): BinaryFileResponse
    {
        $path = $this->path($file->getUploadDir(), $file->getFileName());

        $response = new BinaryFileResponse($path, Response::HTTP_OK, [
            'Cache-Control' => 'private',
            'Content-type' => \mime_content_type($path),
            'Content-Disposition' => \sprintf('attachment; filename="%s";', $file->getOriginalFileName()),
        ]);

        $response->sendHeaders();

        return $response;
    }
}
