<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Tests\Behat\Traits\BrowserTrait;
use App\Tests\Behat\Traits\DataTableTrait;
use App\Tests\Behat\Traits\DebugTrait;
use App\Tests\Behat\Traits\FileTrait;
use App\Tests\Behat\Traits\FixtureTrait as BehatFixtureTrait;
use App\Tests\Behat\Traits\FormTrait;
use App\Tests\Behat\Traits\GeneralAssertTrait;
use App\Tests\Behat\Traits\UserTrait;
use App\Tests\Shared\Traits\FixtureTrait;
use App\Tests\Shared\Traits\ServiceTrait;
use App\Tests\Tools\Loader\DoctrineFixtureLoader;
use App\Tests\Traits\NormalizerTrait;
use Behat\MinkExtension\Context\MinkContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseContext extends MinkContext
{
    use BehatFixtureTrait;
    use BrowserTrait;
    use DataTableTrait;
    use DebugTrait;
    use FileTrait;
    use FixtureTrait;
    use FormTrait;
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

    /**
     * @param class-string $entityName
     */
    public function getRepository(string $entityName): object
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getService('doctrine.orm.entity_manager');

        return $entityManager->getRepository($entityName);
    }

    protected function getReference(string $reference): object
    {
        /** @var DoctrineFixtureLoader $fixtureLoader */
        $fixtureLoader = $this->getService(DoctrineFixtureLoader::class);

        return $fixtureLoader->getReference($reference);
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $inputs
     */
    protected function runCommand(string $name, array $options = [], array $inputs = []): CommandTester
    {
        $application = new Application($this->kernel);

        $command = $application->find($name);
        $commandTester = new CommandTester($command);
        $commandTester->setInputs($inputs);
        $commandTester->execute($options);

        return $commandTester;
    }

    /**
     * @When /^I consume messages$/
     */
    public function iConsumeMessages(): void
    {
        $this->runCommand('messenger:consume', [
            '--time-limit' => 1,
        ]);
    }
}
