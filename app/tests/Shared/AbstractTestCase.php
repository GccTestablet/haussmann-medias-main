<?php

declare(strict_types=1);

namespace App\Tests\Shared;

use App\Entity\User;
use App\Tests\Shared\Traits\FixtureTrait;
use App\Tests\Shared\Traits\ServiceTrait;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractTestCase extends WebTestCase
{
    use FixtureTrait;
    use ProphecyTrait;

    use ServiceTrait;

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

    protected function getFile(string $path): string
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getService('kernel');

        return \sprintf('%s/%s', $kernel->getProjectDir(), $path);
    }

    /**
     * Initialise a Request object and adds it to the RequestStack.
     */
    protected function initialiseRequest(): Request
    {
        $session = new Session(new MockFileSessionStorage());
        $session->start();
        $request = Request::create('/', Request::METHOD_GET);
        $request->setSession($session);

        /** @var RequestStack $requestStack */
        $requestStack = $this->getService(RequestStack::class);
        $requestStack->push($request);

        return $request;
    }
}
