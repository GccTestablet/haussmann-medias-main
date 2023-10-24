<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Setting;

use App\Entity\Setting\Territory;
use App\Service\Setting\TerritoryManager;
use App\Tests\Fixtures\Doctrine\Setting\TerritoryFixture;
use App\Tests\Shared\AbstractTestCase;

class TerritoryManagerTest extends AbstractTestCase
{
    private ?TerritoryManager $territoryManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            TerritoryFixture::class,
        ]);

        $this->territoryManager = $this->getService(TerritoryManager::class);
    }

    public function testFindAll(): void
    {
        $this->assertCount(3, $this->territoryManager->findAll());
        $this->assertContainsOnlyInstancesOf(Territory::class, $this->territoryManager->findAll());
    }
}
