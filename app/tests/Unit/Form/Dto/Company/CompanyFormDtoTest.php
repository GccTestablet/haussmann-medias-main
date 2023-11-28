<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Dto\Company;

use App\Entity\Company;
use App\Form\Dto\Company\CompanyFormDto;
use App\Tests\Shared\AbstractTestCase;

class CompanyFormDtoTest extends AbstractTestCase
{
    public function testNotExistingUser(): void
    {
        $company = new Company();

        $dto = new CompanyFormDto($company, false);

        $this->assertSame($company, $dto->getCompany());
        $this->assertFalse($dto->isExists());
    }

    public function testExistingUser(): void
    {
        $company = new Company();
        $dto = new CompanyFormDto($company, true);

        $this->assertSame($company, $dto->getCompany());
        $this->assertTrue($dto->isExists());
    }

    public function testGetAndSetName(): void
    {
        $dto = new CompanyFormDto(new Company(), false);

        $this->assertNull($dto->getName());

        $dto->setName('TEST');

        $this->assertSame('TEST', $dto->getName());
    }
}
