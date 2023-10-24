<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Setting;

use App\Entity\Setting\AdaptationCostType;
use App\Service\Setting\AdaptationCostTypeManager;
use App\Tests\Fixtures\Doctrine\Setting\AdaptationCostTypeFixture;
use App\Tests\Shared\AbstractTestCase;

class AdaptationCostTypeManagerTest extends AbstractTestCase
{
    private ?AdaptationCostTypeManager $adaptationCostTypeManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            AdaptationCostTypeFixture::class,
        ]);

        $this->adaptationCostTypeManager = $this->getService(AdaptationCostTypeManager::class);
    }

    public function testFindAll(): void
    {
        $this->assertCount(3, $this->adaptationCostTypeManager->findAll());
        $this->assertContainsOnlyInstancesOf(AdaptationCostType::class, $this->adaptationCostTypeManager->findAll());
    }
}
