<?php

declare(strict_types=1);

namespace App\Tests\End2End\Layout;

use App\Tests\End2End\Shared\AbstractEnd2EndTestCase;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;

class MenuRedirectionTest extends AbstractEnd2EndTestCase
{
    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
        ]);
    }

    /**
     * @dataProvider provideRedirection
     */
    public function testRedirection(string $link, string $url): void
    {
        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->client->clickLink($link);
        $this->assertUrl($url);
    }

    /**
     * @return array<array<string, string>>
     */
    public function provideRedirection(): array
    {
        return [
            [
                'link' => 'Contrats d\'acquisition',
                'url' => '/acquisition-contracts',
            ],
            [
                'link' => 'Contrats de distribution',
                'url' => '/profile/distribution-contracts',
            ],
            [
                'link' => 'Oeuvres',
                'url' => '/profile/works',
            ],
            [
                'link' => 'Sociétés',
                'url' => '/companies',
            ],
            [
                'link' => 'Utilisateurs',
                'url' => '/users',
            ],
            [
                'link' => 'Canaux de diffusion',
                'url' => '/settings/broadcast/channels',
            ],
            [
                'link' => 'Territoires',
                'url' => '/settings/territories',
            ],
            [
                'link' => 'Types de coût d\'adaptation',
                'url' => '/settings/adaptation-cost-types',
            ],
        ];
    }
}
