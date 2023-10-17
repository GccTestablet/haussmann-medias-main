<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Parser;

use App\Entity\Company;
use App\Entity\User;
use App\Form\Dto\User\UserFormDto;
use App\Tests\AbstractTestCase;
use App\Tools\Parser\ObjectParser;

class ObjectParserTest extends AbstractTestCase
{
    private ?ObjectParser $objectParser;

    protected function setUp(): void
    {
        $this->objectParser = new ObjectParser();
    }

    public function testGetClassName(): void
    {
        $this->assertSame(User::class, $this->objectParser->getClassName(User::class));
        $this->assertSame(User::class, $this->objectParser->getClassName(new User()));
        $this->assertSame(Company::class, $this->objectParser->getClassName(new Company()));
    }

    public function testGetProperties(): void
    {
        $this->assertSame([
            'id',
            'firstName',
            'lastName',
            'email',
            'roles',
            'password',
            'enabled',
            'lastLogin',
            'lastActivity',
            'passwordRequestedAt',
            'passwordResetToken',
            'connectedOn',
            'companies',
            'userIdentifier',
            'fullName',
            'createdBy',
            'updatedBy',
            'createdAt',
            'updatedAt',
        ], $this->objectParser->getProperties(new User()));

        $this->assertSame([
            'id',
            'name',
            'users',
            'acquisitionContracts',
            'distributionContracts',
            'createdBy',
            'updatedBy',
            'createdAt',
            'updatedAt',
        ], $this->objectParser->getProperties(new Company()));
    }

    public function testMergeFromObject(): void
    {
        $user = (new User())
            ->setLastName('TEST')
            ->setFirstName('Test')
            ->setEmail('test@test.fr')
        ;

        $dto = new UserFormDto($user, false);

        $this->assertNull($dto->getLastName());
        $this->assertNull($dto->getFirstName());
        $this->assertNull($dto->getEmail());

        $this->objectParser->mergeFromObject($user, $dto, ['roles']);
        $this->assertSame('TEST', $dto->getLastName());
        $this->assertSame('Test', $dto->getFirstName());
        $this->assertSame('test@test.fr', $dto->getEmail());
    }
}
