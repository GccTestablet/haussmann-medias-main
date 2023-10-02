<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Dto\Security;

use App\Form\Dto\Security\LoginDto;
use App\Tests\AbstractTestCase;

class LoginDtoTest extends AbstractTestCase
{
    private ?LoginDto $loginDto;

    protected function setUp(): void
    {
        $this->loginDto = new LoginDto();
    }

    public function testGetAndSetEmail(): void
    {
        $this->assertNull($this->loginDto->getEmail());
        $this->loginDto->setEmail('email');
        $this->assertSame('email', $this->loginDto->getEmail());
    }

    public function testGetAndSetPassword(): void
    {
        $this->assertNull($this->loginDto->getPassword());
        $this->loginDto->setPassword('password');
        $this->assertSame('password', $this->loginDto->getPassword());
    }
}
