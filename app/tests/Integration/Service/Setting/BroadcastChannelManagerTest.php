<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Service\Setting\BroadcastChannelManager;
use App\Tests\AbstractTestCase;
use App\Tests\Fixtures\Doctrine\Setting\BroadcastChannelFixture;

class BroadcastChannelManagerTest extends AbstractTestCase
{
    private ?BroadcastChannelManager $broadcastChannelManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            BroadcastChannelFixture::class,
        ]);

        $this->broadcastChannelManager = $this->getService(BroadcastChannelManager::class);
    }

    public function testFindAll(): void
    {
        $this->assertCount(4, $this->broadcastChannelManager->findAll());
        $this->assertContainsOnlyInstancesOf(BroadcastChannel::class, $this->broadcastChannelManager->findAll());
    }

    public function testFindOneByName(): void
    {
        $this->assertInstanceOf(BroadcastChannel::class, $this->broadcastChannelManager->findOneByName('AVOD'));
        $this->assertNull($this->broadcastChannelManager->findOneByName('unknown'));
    }

    public function testFindOneBySlug(): void
    {
        $this->assertInstanceOf(BroadcastChannel::class, $this->broadcastChannelManager->findOneBySlug('avod'));
        $this->assertNull($this->broadcastChannelManager->findOneBySlug('unknown'));
    }
}
