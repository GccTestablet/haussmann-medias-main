<?php

declare(strict_types=1);

namespace App\Tests\End2End\Layout;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;

class MenuVisibilityTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
        ]);
    }

    public function testAdminShouldSeeSettings(): void
    {
        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->assertMenuContains('Contrats d\'acquisition');
        $this->assertMenuContains('Contrats de sous-distribution');
        $this->assertMenuContains('Œuvres');
        $this->assertMenuContains('Sociétés');
        $this->assertMenuContains('Utilisateurs');
        $this->assertMenuContains('Canaux de diffusion');
        $this->assertMenuContains('Territoires');
        $this->assertMenuContains('Types de coûts de distribution');
    }

    public function testNonAdminShouldSeeSettings(): void
    {
        $this->logInAs(UserFixture::SIMPLE_USER);

        $this->assertMenuContains('Contrats d\'acquisition');
        $this->assertMenuContains('Contrats de sous-distribution');
        $this->assertMenuContains('Œuvres');
        $this->assertMenuNotContains('Sociétés');
        $this->assertMenuNotContains('Utilisateurs');
        $this->assertMenuNotContains('Canaux de diffusion');
        $this->assertMenuNotContains('Territoires');
        $this->assertMenuNotContains('Types de coûts de distribution');
    }
}
