<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\Tools\Loader\DoctrineFixtureLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractTestCase extends WebTestCase
{
    protected function getService(string $id): ?object
    {
        return self::getContainer()->get($id);
    }

    /**
     * @param string[] $fixtures
     */
    protected function loadOrmOnDemandFixtures(array $fixtures, bool $append = false): void
    {
        /** @var DoctrineFixtureLoader $fixtureLoader */
        $fixtureLoader = $this->getService(DoctrineFixtureLoader::class);

        $fixtureLoader->loadFixtures($fixtures, $append);
    }

    protected function getFile(string $path): string
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getService('kernel');

        return \sprintf('%s/%s', $kernel->getProjectDir(), $path);
    }
}
