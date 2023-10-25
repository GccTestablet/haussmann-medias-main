<?php

declare(strict_types=1);

namespace App\Tests\Shared\Traits;

use App\Tests\Tools\Loader\DoctrineFixtureLoader;

trait FixtureTrait
{
    /**
     * @param string[] $fixtures
     */
    protected function loadOrmOnDemandFixtures(array $fixtures, bool $append = false): void
    {
        /** @var DoctrineFixtureLoader $fixtureLoader */
        $fixtureLoader = $this->getService(DoctrineFixtureLoader::class);

        $fixtureLoader->loadFixtures($fixtures, $append);
    }

    public function getReference(string $reference): object
    {
        /** @var DoctrineFixtureLoader $fixtureLoader */
        $fixtureLoader = $this->getService(DoctrineFixtureLoader::class);

        return $fixtureLoader->getReference($reference);
    }
}
