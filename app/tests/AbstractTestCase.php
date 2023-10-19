<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use App\Tests\Tools\Loader\DoctrineFixtureLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractTestCase extends WebTestCase
{
    protected function getService(string $id): ?object
    {
        return self::getContainer()->get($id);
    }

    protected function logInAs(string $reference): void
    {
        $this->logInAsUser($this->getReference($reference));
    }

    protected function logInAsUser(User $user): void
    {
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        /** @var TokenStorageInterface $tokenStorage */
        $tokenStorage = $this->getService(TokenStorageInterface::class);
        $tokenStorage->setToken($token);
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

    public function getReference(string $reference): object
    {
        /** @var DoctrineFixtureLoader $fixtureLoader */
        $fixtureLoader = $this->getService(DoctrineFixtureLoader::class);

        return $fixtureLoader->getReference($reference);
    }

    protected function getFile(string $path): string
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getService('kernel');

        return \sprintf('%s/%s', $kernel->getProjectDir(), $path);
    }
}
