<?php

declare(strict_types=1);

namespace App\Tests\End2End\Shared;

use App\Entity\User;
use App\Tests\End2End\Shared\Traits\AssertActionTrait;
use App\Tests\End2End\Shared\Traits\AssertTableTrait;
use App\Tests\End2End\Shared\Traits\AssertTrait;
use App\Tests\Shared\Traits\FixtureTrait;
use App\Tests\Shared\Traits\ServiceTrait;
use App\Tests\Tools\Loader\DoctrineFixtureLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler as PantherCrawler;
use Symfony\Component\Panther\PantherTestCase;

abstract class AbstractEnd2EndTestCase extends PantherTestCase
{
    use AssertActionTrait;
    use AssertTableTrait;
    use AssertTrait;

    use FixtureTrait;
    use ServiceTrait;

    private const BASE_URI = 'http://test.haussmann-medias.local';

    protected ?Client $client = null;

    protected ?PantherCrawler $crawler = null;

    protected function getClient(): void
    {
        $this->client = static::createPantherClient([
            'external_base_uri' => self::BASE_URI,
            'browser' => static::CHROME,
        ]);
    }

    protected function iAmOn(string $path, string $method = Request::METHOD_GET): void
    {
        $this->getClient();

        $this->crawler = $this->client->request($method, $path);
    }

    protected function logInAs(string $userReference): void
    {
        $this->logInAsUser($this->getReference($userReference));
    }

    protected function logInAsUser(User $user): void
    {
        $this->iAmOn('/login');
        $form = $this->crawler->selectButton('Connexion')->form();
        $form['login_form[email]'] = $user->getEmail();
        $form['login_form[password]'] = 'Qwerty123';
        $this->client->submit($form);

        $this->iAmOn('/');
    }

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
}
