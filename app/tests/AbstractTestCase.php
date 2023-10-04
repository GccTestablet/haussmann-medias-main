<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractTestCase extends WebTestCase
{
    protected function getFile(string $path): string
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getService('kernel');

        return \sprintf('%s/%s', $kernel->getProjectDir(), $path);
    }

    protected function getService(string $id): ?object
    {
        return self::getContainer()->get($id);
    }
}
