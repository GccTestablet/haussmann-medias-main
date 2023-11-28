<?php

declare(strict_types=1);

namespace App\Tests\Integration\Pager;

use App\Model\Pager\FilterCollection;
use App\Pager\Shared\PagerInterface;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractPagerTest extends AbstractTestCase
{
    protected static string $pagerClass;

    /**
     * @var array<string>
     */
    protected static array $fixtures = [];

    protected static string $logInAs = UserFixture::SUPER_ADMIN_USER;

    protected ?PagerInterface $pager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures(static::$fixtures);

        $this->pager = $this->getService(static::$pagerClass);

        $this->logInAs(static::$logInAs);

        $request = $this->initialiseRequest();

        $this->pager->init($request, new FilterCollection());
        $this->pager->buildQuery();
    }

    /**
     * @dataProvider provideGetColumns
     */
    public function testGetColumns(string $id, string $expected): void
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $this->assertSame(
            $expected,
            $serializer->serialize($this->pager->getColumns()[$id], 'json')
        );
    }

    /**
     * @return array<array{id: string, expected: string}>
     */
    abstract public function provideGetColumns(): array;
}
