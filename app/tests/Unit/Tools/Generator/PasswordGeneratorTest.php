<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Generator;

use App\Tests\Shared\AbstractTestCase;
use App\Tools\Generator\PasswordGenerator;

class PasswordGeneratorTest extends AbstractTestCase
{
    /**
     * @dataProvider providePassword
     */
    public function testPassword(int $length, bool $lowerCases, bool $upperCases, bool $digits): void
    {
        $generatedPassword = PasswordGenerator::generate($length, $lowerCases, $upperCases, $digits);
        $this->assertSame($length, \strlen($generatedPassword));
        $this->assertSame($lowerCases, (bool) \preg_match('/[a-z]/', $generatedPassword));
        $this->assertSame($upperCases, (bool) \preg_match('/[A-Z]/', $generatedPassword));
        $this->assertSame($digits, (bool) \preg_match('/\d/', $generatedPassword));
    }

    /**
     * @return array<string, array<string, int|bool>>
     */
    public function providePassword(): array
    {
        return [
            'All chars' => [
                'length' => 8,
                'lowerCases' => true,
                'upperCases' => true,
                'digits' => true,
            ],
            'Lower cases only' => [
                'length' => 8,
                'lowerCases' => true,
                'upperCases' => false,
                'digits' => false,
            ],
            'Digits only' => [
                'length' => 8,
                'lowerCases' => false,
                'upperCases' => false,
                'digits' => true,
            ],
            'Lower and upper cases only' => [
                'length' => 8,
                'lowerCases' => true,
                'upperCases' => true,
                'digits' => false,
            ],
            '15 length long' => [
                'length' => 15,
                'lowerCases' => true,
                'upperCases' => true,
                'digits' => true,
            ],
        ];
    }
}
