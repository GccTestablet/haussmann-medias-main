<?php

declare(strict_types=1);

namespace App\Tests\End2End\Security;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\UserFixture;

class LoginTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
        ]);

        $this->iAmOn('/login');
    }

    public function testInvalidLogin(): void
    {
        $form = $this->crawler->selectButton('Connexion')->form();
        $form['login_form[email]'] = 'super-admin@hm.mail';
        $form['login_form[password]'] = 'INVALID_PASSWORD';
        $crawler = $this->client->submit($form);

        $this->assertStringContainsString('Identifiants invalides.', $crawler->filter('.alert')->text());
    }

    public function testLogin(): void
    {
        $form = $this->crawler->selectButton('Connexion')->form();
        $form['login_form[email]'] = 'super-admin@hm.mail';
        $form['login_form[password]'] = 'Qwerty123';
        $crawler = $this->client->submit($form);

        $this->assertStringContainsString('Dashboard', $crawler->filter('h1')->text());
    }
}
