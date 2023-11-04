<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Tests\Behat\Traits\BrowserTrait;
use App\Tests\Behat\Traits\DataTableTrait;
use App\Tests\Behat\Traits\FixtureTrait as BehatFixtureTrait;
use App\Tests\Behat\Traits\GeneralAssertTrait;
use App\Tests\Behat\Traits\UserTrait;
use App\Tests\Shared\Traits\FixtureTrait;
use App\Tests\Shared\Traits\ServiceTrait;
use App\Tests\Tools\Loader\DoctrineFixtureLoader;
use App\Tests\Traits\NormalizerTrait;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseContext extends MinkContext
{
    use BehatFixtureTrait;
    use BrowserTrait;
    use DataTableTrait;
    use FixtureTrait;
    use GeneralAssertTrait;
    use NormalizerTrait;
    use ServiceTrait;
    use UserTrait;

    public function __construct(
        private readonly KernelInterface $kernel
    ) {}

    protected function getContainer(): ContainerInterface
    {
        return $this->kernel->getContainer();
    }

    protected function getService(string $id): object
    {
        return $this->getContainer()->get($id);
    }

    protected function getReference(string $reference): object
    {
        /** @var DoctrineFixtureLoader $fixtureLoader */
        $fixtureLoader = $this->getService(DoctrineFixtureLoader::class);

        return $fixtureLoader->getReference($reference);
    }
}
